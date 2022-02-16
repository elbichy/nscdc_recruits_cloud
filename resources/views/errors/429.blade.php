@extends('errors.layout')

@section('title', __('Too Many Requests'))
@section('icon', 'error_icon fas fa-server fa-4x')
@section('code', '429')
@section('message', __('Too Many Requests'))
