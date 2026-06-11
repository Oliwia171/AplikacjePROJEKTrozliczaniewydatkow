<?php

use App\Models\Bill;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('demo:bulk-expenses {--groups=10} {--bills=1000} {--members=5}', function () {
    $groupsCount = max(1, min((int) $this->option('groups'), 200));
    $billsCount = max(1, min((int) $this->option('bills'), 5000));
    $membersPerGroup = max(1, min((int) $this->option('members'), 20));
    $runId = now()->format('YmdHis').'-'.Str::lower(Str::random(6));
    $now = now();

    DB::transaction(function () use ($groupsCount, $billsCount, $membersPerGroup, $runId, $now) {
        $owner = User::firstOrCreate(
            ['email' => 'bulk-owner@example.com'],
            ['name' => 'Bulk Owner', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        $members = collect(range(1, $membersPerGroup))->map(fn (int $number) => User::firstOrCreate(
            ['email' => "bulk-member-{$number}@example.com"],
            ['name' => "Bulk Member {$number}", 'password' => Hash::make('password'), 'role' => 'user']
        ));

        $groups = collect(range(1, $groupsCount))->map(function (int $number) use ($owner, $members, $runId) {
            $group = Group::create([
                'name' => "Import {$runId} grupa {$number}",
                'description' => 'Grupa wygenerowana komenda demo:bulk-expenses.',
                'owner_id' => $owner->id,
            ]);

            $group->users()->syncWithoutDetaching($members->pluck('id')->push($owner->id)->all());

            return $group;
        });

        $memberIdsByGroup = $groups->mapWithKeys(fn (Group $group) => [
            $group->id => $group->users()->pluck('users.id')->all(),
        ]);

        $billRows = [];
        for ($i = 1; $i <= $billsCount; $i++) {
            $group = $groups[($i - 1) % $groups->count()];
            $memberIds = $memberIdsByGroup[$group->id];
            $amount = random_int(100, 20000) / 100;

            $billRows[] = [
                'group_id' => $group->id,
                'payer_id' => $memberIds[array_rand($memberIds)],
                'description' => "Import {$runId} wydatek {$i}",
                'amount' => $amount,
                'date' => $now->copy()->subDays($i % 90)->toDateString(),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        collect($billRows)->chunk(500)->each(fn ($chunk) => DB::table('bills')->insert($chunk->all()));

        $splitRows = [];
        Bill::query()
            ->where('description', 'like', "Import {$runId} wydatek %")
            ->orderBy('id')
            ->get(['id', 'group_id', 'payer_id', 'amount'])
            ->each(function (Bill $bill) use (&$splitRows, $memberIdsByGroup, $now) {
                $memberIds = $memberIdsByGroup[$bill->group_id];
                $share = round((float) $bill->amount / count($memberIds), 2);

                foreach ($memberIds as $memberId) {
                    $splitRows[] = [
                        'bill_id' => $bill->id,
                        'user_id' => $memberId,
                        'amount' => $share,
                        'is_paid' => $memberId === $bill->payer_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            });

        collect($splitRows)->chunk(1000)->each(fn ($chunk) => DB::table('bill_splits')->insert($chunk->all()));

        if (DB::getDriverName() !== 'mysql') {
            collect($billRows)
                ->groupBy('group_id')
                ->each(fn ($rows, int $groupId) => DB::table('groups')
                    ->where('id', $groupId)
                    ->increment('total_amount', $rows->sum('amount')));
        }
    });

    $this->info("Dodano {$billsCount} wydatkow w {$groupsCount} grupach.");
    $this->line('Wydajnosc: uzyto insertow partiami, jednej transakcji i chunkow dla splitow.');
})->purpose('Generate many demo groups and expenses in batches');
