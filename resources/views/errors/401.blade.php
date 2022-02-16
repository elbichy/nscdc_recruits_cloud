@extends('errors.layout')

@section('title', __('Unauthorized'))
@section('icon', 'error_icon fas fa-user-lock fa-4x')
@section('code', '401')
@section('message', __('Unauthorized'))
