<?php
// app/controllers/Calendar.php

class Calendar extends Controller {
    public function index() {
        header('Location: ' . BASEURL . '/task/calendar');
        exit;
    }
}
