<?php

class Alarms extends Controller {

    // current time data
    private $td = [];

    public function __construct() {
        require APPROOT . '/helpers/mail.php';
        $this->alarmModel = $this->model('Alarm');
        $current = date('Y-m-d H:i');
        $tomorrow = strtotime($current . ' +1 day');
        $this->td = [
            'current' => $current,
            'current_unix_ts' => strtotime($current),
            'tomorrow' => $tomorrow,
            'current_datehour' => date('Y-m-d H'),
            'current_10min' => floor(date('i') / 10),
            'current_hour' => date('H'),
            'current_dow' => date('l'),
            'current_dom' => date('d'),
            'current_doy' => date('z'),
            'tomorrow_month' => date('m', $tomorrow),
            'current_month' => date('m'),
            'tomorrow_year' => date('Y', $tomorrow),
            'current_year' => date('Y')
        ];
    }

    public function loop() {
        $alarms = $this->alarmModel->getUserAlarms();
        foreach ($alarms as $alarm) {
            $expiry_unix_ts = strtotime($alarm->expiry);
            $expiry_datehour = date('Y-m-d H', $expiry_unix_ts);
            $expiry_10min = floor(date('i', $expiry_unix_ts) / 10);
            $expiry_hour = date('H', $expiry_unix_ts);
            $expiry_dow = date('l', $expiry_unix_ts);
            $expiry_dom = date('d', $expiry_unix_ts);
            $expiry_doy = date('z', $expiry_unix_ts);
            $day_diff = floor(abs($this->td['current_unix_ts'] - $expiry_unix_ts) / 86400);

            $send_now = (
                    ($this->td['current_datehour'] == $expiry_datehour && $this->td['urrent_10min'] == $expiry_10min) || (
                    ($this->td['current_hour'] == $expiry_hour && $this->td['current_10min'] == $expiry_10min) && (
                    (is_numeric($alarm->repeat) && $day_diff % $alarm->repeat == 0) ||
                    ($alarm->repeat == 'Täglich') ||
                    ($alarm->repeat == 'Wöchentlich' && $this->td['current_dow'] == $expiry_dow) ||
                    ($alarm->repeat == 'Monatlich' && ($this->td['current_dom'] == $expiry_dom ||
                    ($expiry_dom > 28 && $this->td['tomorrow_month'] != $this->td['current_month']))) ||
                    ($alarm->repeat == 'Jährlich' && ($this->td['current_doy'] == $expiry_doy ||
                    ($expiry_doy == 365 && $this->td['tomorrow_year'] != $this->td['current_year'])))))
                    );

            $this->please($send_now, $alarm->email, $alarm->username, $alarm->expiry, $alarm->noteid);
        }
    }

    public function please($send, $to, $name, $subj, $id) {
        echo 'HIER please';
        echo "$send, $to, $name, $subj, $id<br/>";
        if ($send == true) {
            echo 'send = true <br>';
            $from = 'note@ivanne.de';
            $msg = '<a href="' . URLROOT . '">' . URLROOT . '</a>';
            if (smtpMail($to, $from, $name, $subj, $msg) && $this->alarmModel->updateSent($id)) {
                echo 'YEY';
            } else {
                smtpMail('mail@ivanne.de', $from, 'ivanne',
                        '10-min-send-mail-cronjob-fail', 'error ' . $id);
            }
        }
    }

}
