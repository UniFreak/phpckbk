<?php
class LittleCalendar {
    protected $monthToUse;
    protected $prepared = false;
    protected $days = [];

    public function __construct($month, $year)
    {
        $this->monthToUse = DateTime::createFromFormat('Y-m|', sprintf("%04d-%02d", $year, $month));
        $this->prepare();
    }

    protected function prepare()
    {
        // info about each day in the month
        // including appropriate padding at the beginning and end

        // first, days of the week across the first row
        foreach (['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'] as $dow) {
            $endOfRow = ($dow == 'Sa');
            $this->days[] = ['type' => 'dow', 'label' => $dow, 'endOfRow' => $endOfRow];
        }

        // next, placeholder up to the first day of the week
        for ($i = 0, $j = $this->monthToUse->format('w'); $i < $j; $i++) { // w: dow, 0-6
            $this->days[] = ['type' => 'blank'];
        }

        // then, one item for each day in the month
        $today = date('Y-m-d');
        $days = new DatePeriod(
            $this->monthToUse,
            new DateInterval('P1D'),
            $this->monthToUse->format('t') - 1 // t: month length in days
        );
        foreach ($days as $day) {
            $isToday = ($day->format('Y-m-d') == $today);
            $endOfRow = ($day->format('w') == 6);
            $this->days[] = [
                'type' => 'day',
                'label' => $day->format('j'), // j: day of month, 1-31
                'today' => $isToday,
                'endOfRow' => $endOfRow
            ];
        }

        // last, any placeholders for the end of the month if we didn't have an
        // endOfWeek day as the last day in the month
        if (! $endOfRow) {
            for ($i = 0, $j = 6 - $day->format('w'); $i < $j; $i++) {
                $this->days[] = ['type' => 'blank'];
            }
        }
    }

    public function text()
    {
        $lineLength = strlen('Su Mo Tu We Th Fr Sa');
        $header = $this->monthToUse->format('F Y');
        $headerSpacing = floor(($lineLength - strlen($header)) / 2); // center
        $text = str_repeat(' ', $headerSpacing) . $header . "\n";
        foreach ($this->days as $i => $day) {
            switch ($day['type']) {
                case 'dow':
                    $text .= sprintf("% 2s", $day['label']);
                    break;
                case 'blank':
                    $text .= ' ';
                    break;
                case 'day':
                    $text .= sprintf("% 2d", $day['label']);
                    break;
            }
            $text .= (isset($day['endOfRow']) && $day['endOfRow']) ? "\n": " ";
        }
        if ($text[strlen($text)-1] != "\n") {
            $text .= "\n";
        }
        return $text;
    }
}

$cal = new LittleCalendar(10, 1990);
print $cal->text();