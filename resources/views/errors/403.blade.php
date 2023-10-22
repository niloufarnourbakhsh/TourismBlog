@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))

{{--شما اجازه ی دسترسی به این صفحه را ندارید--}}
