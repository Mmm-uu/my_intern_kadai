<?php
class Controller_Base extends Controller
{
    public function before()
    {
        parent::before();
        header('X-Frame-Options: DENY');
    }
}
?>