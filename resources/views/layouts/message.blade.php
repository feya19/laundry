@if (session('success_message'))
	<div class="alert alert-success fade show">
		<div class="alert-icon"><i class="fa fa-check"></i></div>
		<div class="alert-content">{{ session('success_message') }}</div>
		<button type="button" class="btn btn-text-light btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>
	</div>
@elseif (session('error_message'))
	<div class="alert alert-danger fade show">
		<div class="alert-icon"><i class="fa fa-ban"></i></div>
		<div class="alert-content">{{ session('error_message') }}</div>
		<button type="button" class="btn btn-text-light btn-icon alert-dismiss" data-dismiss="alert"><i class="fa fa-times"></i></button>
	</div>
@endif