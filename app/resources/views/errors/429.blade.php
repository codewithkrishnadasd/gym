@extends('errors::minimal')

@section('title', 'Too many requests')
@section('code', '429')
@section('message', 'That was a lot of requests in a short time. Wait a moment and try again.')

