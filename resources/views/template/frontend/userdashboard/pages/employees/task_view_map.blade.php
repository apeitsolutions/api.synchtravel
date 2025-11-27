<?php

//    print_r($employees->address);
//    die();
//if (isset($_POST["submit_address"]))
//{
//$address = $_POST["address"];
//$address = str_replace(" ", "+", $address);
?>


@extends('template/frontend/userdashboard/layout/default')
@section('content')
    <iframe width="100%" height="500" src="https://maps.google.com/maps?q=<?php echo $task->task_address; ?>&output=embed"></iframe>


@endsection

