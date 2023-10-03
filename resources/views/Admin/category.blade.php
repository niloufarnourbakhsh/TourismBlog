@extends('includes.admin-base')
@section('title','گروه بندی')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="d-flex flex-column align-self-end mb-3 mt-3">
                    <h6 class="text-black p-2 rounded mb-3 my-2">
                        اضافه کردن گروه جدید
                    </h6>
                    <div>
                        <form action="{{route('categories.store')}}" method="post">
                            @csrf
                            <label for="name" class="text-white">نام گروه</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <div class="d-grid gap-2 mt-2">
                                <button class="btn bg-green text-white" type="submit">ذخیره</button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
            <div class="col-6">
                <h6 class=" text-black p-2 rounded mb-3 my-2 mt-3" >طبقه بندی موجود</h6>
                <table>
                    <thead>
                    <th class="px-1">ID</th>
                    <th colspan="2">اسم</th>
                    <th>حذف</th>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                        <td>{{$category->id}}</td>
                        <td colspan="2">
                            <form action="{{route('categories.update',[$category->id])}}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="d-flex flex-row text-right justify-content-around">
                                    <input type="text" name="name"  value="{{$category->name}}"
                                           class="form-control" required>
                                    <button type="submit" class="btn bg-green text-white">ویرایش </button>
                                </div>
                            </form>
                        </td>

                        <td>
                            <form action="{{route('categories.destroy',[$category->id])}}" method="post">
                                @csrf
                                @method("DELETE")
                                <button class="btn">
                                    <i class="fas fa-minus-circle fa-2x text-danger"></i>
                                </button>
                            </form>
                        </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
