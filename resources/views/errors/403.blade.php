@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('icon', 'error_icon fas fa-server fa-4x')
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
