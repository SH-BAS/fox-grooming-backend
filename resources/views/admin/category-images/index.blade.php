@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">Category Images</h4>
                <a href="{{ route('admin.category-images.create') }}" class="btn btn-primary btn-sm">+ Add Image</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th><strong>ID</strong></th>
                                <th><strong>Category</strong></th>
                                <th><strong>Image</strong></th>
                                <th><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($images as $index => $image)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>{{ $image->category->name ?? 'N/A' }}</td>
                                <td>
                                    @if($image->image)
                                        <img src="{{ asset('storage/' . $image->image) }}" width="50" alt="">
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.category-images.destroy', $image->id) }}" method="POST" data-confirm="Are you sure?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
