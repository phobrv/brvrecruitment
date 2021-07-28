@extends('phobrv::layout.app')

@section('header')
<a href="{{route('recruitment.index')}}"  class="btn btn-default float-left">
	<i class="fa fa-backward"></i> @lang('Back')
</a>
<a href="#" onclick="save()"  class="btn btn-primary float-left">
	<i class="fa fa-floppy-o"></i> @lang('Save & Close')
</a>
<a href="#" onclick="update()"  class="btn btn-warning float-left">
	<i class="fa fa-wrench"></i> @lang('Update')
</a>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-body">
		<div class="row">

			<form class="form-horizontal" id="formSubmit" method="post" action="{{isset($data['post']) ? route('recruitment.update',array('recruitment'=>$data['post']->id)) : route('recruitment.store')}}"  enctype="multipart/form-data">
				@csrf
				@isset($data['post']) @method('put') @endisset
				<input type="hidden" id="typeSubmit" name="typeSubmit" value="">
				<div class="col-md-8">
					@isset($data['post'])
					@include('phobrv::input.inputText',['label'=>'Url','key'=>'slug'])
					@endif
					@include('phobrv::input.inputText',['label'=>'Title','key'=>'title','required'=>true])
					@isset($data['post'])
					@include('phobrv::input.inputText',['label'=>'Create date','key'=>'created_at','datepicker'=>true])
					@endif
					@include('phobrv::input.inputTextarea',['key'=>'content','label'=>'Content','style'=>'short'])
					@include('phobrv::input.label',['label'=>'Seo Meta'])
					@include('phobrv::input.inputText',['label'=>'Meta Title','key'=>'meta_title','type'=>'meta'])
					@include('phobrv::input.inputText',['label'=>'Meta Description','key'=>'meta_description','type'=>'meta'])
					@include('phobrv::input.inputText',['label'=>'Meta Keywords','key'=>'meta_keywords','type'=>'meta'])
				</div>
				<div class="col-md-4">
					@include('phobrv::input.inputImage',['key'=>'thumb','basic'=>true])
					@include('phobrv::input.label',['label'=>'Meta info'])
					@include('phobrv::input.inputText',['label'=>'Số lượng','key'=>'number','formType'=>'basic','inputType'=>'number','type'=>'meta'])
					@include('phobrv::input.inputText',['label'=>'Nơi làm việc','key'=>'office','formType'=>'basic','type'=>'meta'])
					@include('phobrv::input.inputText',['label'=>'Cấp bậc','key'=>'rank','formType'=>'basic','type'=>'meta'])
					@include('phobrv::input.inputText',['label'=>'Bằng cấp','key'=>'degree','formType'=>'basic','type'=>'meta'])
					@include('phobrv::input.inputText',['label'=>'Mức lương','key'=>'wage','formType'=>'basic','type'=>'meta'])
					@include('phobrv::input.inputText',['label'=>'Han nộp hồ sơ','key'=>'endTime','formType'=>'basic','datepicker'=>'true','type'=>'meta'])

				</div>
				<button id="btnSubmit" style="display: none" type="submit" ></button>
			</form>
		</div>
	</div>
</div>
@endsection

@section('styles')
<style type="text/css">
	#listTagShow .btn-flat{
		margin-top: 3px;
		margin-bottom: 5px;
	}
	#listTagShow .show{
		position: relative;
		padding-right: 15px;
		float: left;
	}
	#listTagShow .show i{
		position: absolute;
		z-index: 1;
		top: -5px;
		right: 3px;
		color: red;
	}
</style>
@endsection

@section('scripts')
<script type="text/javascript">

	window.onload = function() {
		CKEDITOR.replace( 'content' ,{
			filebrowserBrowseUrl : '/elfinder/ckeditor',
			height: '400px',
		});
	};

</script>
@endsection