@extends('layouts.app')

@section('title', '设置')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>账户设置</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">用户名</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">邮箱</label>
                        <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
                        <small class="form-text text-muted">邮箱地址不可修改</small>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">手机号（用于接收开播通知）</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">请填写正确的手机号，用于接收开播短信通知</small>
                    </div>

                    <button type="submit" class="btn btn-primary">保存设置</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h4>使用说明</h4>
            </div>
            <div class="card-body">
                <ol>
                    <li>在控制台中添加您关注的抖音主播</li>
                    <li>系统会自动检测主播的直播状态</li>
                    <li>当主播开始直播时，系统会向您发送短信通知</li>
                    <li>您可以随时暂停或删除监控</li>
                    <li>请确保手机号正确，否则无法接收通知</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection