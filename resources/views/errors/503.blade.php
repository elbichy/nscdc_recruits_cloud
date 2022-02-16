@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('icon', 'error_icon fas fa-server fa-4x')
@section('code', '503')
@section('message', __($exception->getMessage() ?: 'Service Unavailable'))
