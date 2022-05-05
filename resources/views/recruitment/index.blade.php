@extends('phobrv::adminlte3.layout')

@section('header')
<ul>
	<li>
		<a href="{{route('recruitment.create')}}"  class="btn btn-primary float-left">
		    <i class="far fa-edit"></i> @lang('Create new')
		</a>
	</li>
</ul>
@endsection

@section('content')
<div class="card">
	<div class="card-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>{{__('Date')}}</th>
					<th>{{__('Title')}}</th>
					<th>{{__('Author')}}</th>
					<th>{{__('Status')}}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if($data['posts'])
				@foreach($data['posts'] as $r)
				<tr>
					<td align="center">{{$loop->index+1}}</td>
					<td align="center">{{date('d/m/Y',strtotime($r->created_at))}}</td>
					<td width="40%">
						<a href="{{route('level1',['slug'=>$r->slug])}}">
							{{$r->title}}
						</a>
					</td>

					<td>{{$r->user->name ?? ''}}</td>
					<td align="center">
						@if($r->status == 1)
						<a href="#" onclick="changeStatus('{{$r->id}}', this)">
						<i class="fa fa-check-circle" ></i>
						</a>
						@else
						<a href="#" style="color:red" onclick="changeStatus('{{$r->id}}', this)">
						<i class="fa fa-minus-circle"></i>
						</a>
						@endif
					</td>
					<td style="width: 50px;"  align="center">
						<a href="{{route('recruitment.edit',array('recruitment'=>$r->id))}}"><i class="far fa-edit" title="Sửa"></i></a>
						&nbsp;&nbsp;&nbsp;
						<a style="color: red" href="#" onclick="destroy('destroy{{$r->id}}')"><i class="fa fa-times" title="Sửa"></i></a>
						<form id="destroy{{$r->id}}" action="{{ route('recruitment.destroy',array('recruitment'=>$r->id)) }}" method="post" style="display: none;">
							@method('delete')
		                    @csrf
		                </form>
					</td>
				</tr>
				@endforeach
				@endif
			</tbody>

		</table>
	</div>
</div>
@endsection

@section('styles')
<style type="text/css">

</style>
@endsection

@section('scripts')
<script type="text/javascript">
	function destroy(form){
		var anwser =  confirm("Bạn muốn xóa bài viết này?");
		if(anwser){
			event.preventDefault();
			document.getElementById(form).submit();
		}
	}
	function changeStatus(id, obj){
		var result = confirm("Bạn có muốn thay đổi trạng thái của bài viết này?");
		if (result == true) {
			$.ajax({
				headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				url: '{{URL::route("post.changeStatus")}}',
				type: 'POST',
				data: {id: id},
				success: function(output){
					console.log(output);
					if (output == 1){
						$(obj).css('color','blue');
						$(obj).html('');
						$(obj).append('<i class="fa fa-check-circle"></i>');
					} else{
						$(obj).css('color','red');
						$(obj).html('');
						$(obj).append('<i class="fa fa-minus-circle"></i>');
					}
				}
			});
		}
	}
</script>
@endsection