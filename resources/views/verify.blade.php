@extends('layouts.main')

@section('content')
<h3>Verification Result</h3>
<p>This document is genuine and contains the following information:</p>

<table>
	<tbody>
		<tr>
			<td>Type:</td>
			<td>{{ucwords($doc->type)}} Redeployment </td>
		</tr>
		<tr>
			<td>Fullname:</td>
			<td>{{ucwords($doc->fullname)}}</td>
		</tr>
		<tr>
			<td>Service No:</td>
			<td>{{ucwords($doc->service_number)}}</td>
		</tr>
		<tr>
			<td>Rank:</td>
			<td>{{ucwords($doc->rank)}}</td>
		</tr>
		<tr>
			<td>Redeployment From:</td>
			<td>{{ucwords($doc->from)}}</td>
		</tr>
		<tr>
			<td>Redeployment To</td>
			<td>{{ucwords($doc->to.' '.$doc->designation)}}</td>
		</tr>
		<tr>
			<td>Signatory</td>
			<td>{{strtoupper($doc->signatory)}}</td>
		</tr>
		<tr>
			<td>Date Produced</td>
			<td>{{ucwords($doc->created_at)}}</td>
		</tr>
	</tbody>
</table>
@endsection