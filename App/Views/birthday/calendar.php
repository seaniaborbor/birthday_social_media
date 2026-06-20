<?= $this->extend('layouts/header') ?>

<?= $this->section('content') ?>

<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 48px; margin-bottom: 8px;">🎂 Birthday Calendar</h1>
        <p class="font-mono-courier" style="opacity: 0.7;">
            Celebrating <?= $birthMonth ?> born members
        </p>
        <div class="stamp" style="position: static; display: inline-block; margin-top: 16px;">
            <?= $birthdayCount ?> MEMBERS
        </div>
    </div>
    
    <!-- Birthday Month Banner -->
    <?php if ($isBirthMonth): ?>
        <div class="alert alert-success" style="text-align: center; margin-bottom: 30px;">
            <span class="material-symbols-outlined" style="vertical-align: middle;">celebration</span>
            It's <?= $birthMonth ?>! Celebrate with our birthday members!
        </div>
    <?php else: ?>
        <div class="alert alert-info" style="text-align: center; margin-bottom: 30px;">
            <span class="material-symbols-outlined" style="vertical-align: middle;">event</span>
            <?= $daysUntilBirthMonth ?> days until <?= $birthMonth ?> celebrations begin!
        </div>
    <?php endif; ?>
    
    <!-- Calendar Grid -->
    <div style="background: var(--color-surface); border: 1px solid var(--color-outline); padding: 20px; margin-bottom: 40px;">
        <div style="display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; margin-bottom: 16px;">
            <div class="font-mono-courier" style="font-weight: bold;">Sun</div>
            <div class="font-mono-courier" style="font-weight: bold;">Mon</div>
            <div class="font-mono-courier" style="font-weight: bold;">Tue</div>
            <div class="font-mono-courier" style="font-weight: bold;">Wed</div>
            <div class="font-mono-courier" style="font-weight: bold;">Thu</div>
            <div class="font-mono-courier" style="font-weight: bold;">Fri</div>
            <div class="font-mono-courier" style="font-weight: bold;">Sat</div>
        </div>
        
        <?php
        $firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
        $daysInMonth = date('t', $firstDayOfMonth);
        $startingDay = date('w', $firstDayOfMonth);
        $today = date('j');
        $currentMonthNum = date('n');
        ?>
        
        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px;">
            <?php for ($i = 0; $i < $startingDay; $i++): ?>
                <div style="padding: 12px; opacity: 0.3;"></div>
            <?php endfor; ?>
            
            <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
                <?php
                $hasBirthdays = isset($birthdaysByDay[$day]) && !empty($birthdaysByDay[$day]);
                $isToday = ($currentMonthNum == date('n') && $day == $today);
                $dayBirthdayCount = $hasBirthdays ? count($birthdaysByDay[$day]) : 0;
                ?>
                <div style="padding: 8px; border: 1px solid var(--color-outline); min-height: 80px; background: <?= $isToday ? 'rgba(29, 78, 216, 0.1)' : 'transparent' ?>;">
                    <div style="font-weight: bold; margin-bottom: 8px; <?= $isToday ? 'color: var(--color-primary);' : '' ?>">
                        <?= $day ?>
                        <?php if ($isToday): ?>
                            <span class="material-symbols-outlined" style="font-size: 14px; vertical-align: middle;">today</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($hasBirthdays): ?>
                        <div style="font-size: 11px;">
                            <?php foreach ($birthdaysByDay[$day] as $birthday): ?>
                                <div style="margin-bottom: 4px;">
                                    <span class="material-symbols-outlined" style="font-size: 12px; vertical-align: middle;">cake</span>
                                    <?= esc($birthday['first_name']) ?>
                                    <?php if ($dayBirthdayCount > 1 && $birthday == end($birthdaysByDay[$day])): ?>
                                        <span style="opacity: 0.7;">+<?= $dayBirthdayCount - 1 ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
    
    <!-- Upcoming Birthdays List -->
    <?php if (!empty($upcomingBirthdays)): ?>
        <div class="polaroid-card" style="margin-bottom: 40px;">
            <h3 style="margin-bottom: 20px;">📅 Upcoming Birthdays</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--color-outline);">
                            <th style="padding: 12px; text-align: left;">Member</th>
                            <th style="padding: 12px; text-align: left;">Day</th>
                            <th style="padding: 12px; text-align: left;">Days Until</th>
                            <th style="padding: 12px; text-align: left;">County</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($upcomingBirthdays as $upcoming): ?>
                            <tr style="border-bottom: 1px dotted var(--color-outline);">
                                <td style="padding: 12px;">
                                    <?= esc($upcoming['member']['first_name']) ?> <?= esc($upcoming['member']['last_name']) ?>
                                </td>
                                <td style="padding: 12px;"><?= $upcoming['member']['birth_day'] ?></td>
                                <td style="padding: 12px;">
                                    <span class="btn-vintage" style="padding: 2px 8px; font-size: 11px;">
                                        <?= $upcoming['days_until'] ?> days
                                    </span>
                                </td>
                                <td style="padding: 12px;"><?= esc($upcoming['member']['county']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Birthday Factoids -->
    <div class="card-grid" style="grid-template-columns: repeat(3, 1fr);">
        <div class="polaroid-card" style="text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 48px;">group</span>
            <h3 style="margin: 12px 0;"><?= $birthdayCount ?></h3>
            <p class="font-mono-courier">Total <?= $birthMonth ?> Members</p>
        </div>
        
        <div class="polaroid-card" style="text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 48px;">cake</span>
            <h3 style="margin: 12px 0;">
                <?php
                $maxDay = !empty($birthdaysByDay) ? array_reduce(array_keys($birthdaysByDay), function($carry, $day) use ($birthdaysByDay) {
                    $count = count($birthdaysByDay[$day]);
                    if ($carry === null || $count > $carry['count']) {
                        return ['day' => $day, 'count' => $count];
                    }
                    return $carry;
                }, null) : null;
                ?>
                <?= $maxDay ? $maxDay['day'] . 'th' : 'N/A' ?>
            </h3>
            <p class="font-mono-courier">Most Popular Birthday</p>
        </div>
        
        <div class="polaroid-card" style="text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 48px;">event</span>
            <h3 style="margin: 12px 0;"><?= $daysUntilBirthMonth ?></h3>
            <p class="font-mono-courier">Days Until <?= $birthMonth ?></p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->include('layouts/footer') ?>