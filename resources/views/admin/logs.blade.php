@extends('admin.layouts.app')

@section('title', '监控日志')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>监控日志</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>时间</th>
                        <th>用户</th>
                        <th>主播</th>
                        <th>之前状态</th>
                        <th>当前状态</th>
                        <th>通知发送</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $log->user->name }}</td>
                            <td>{{ $log->streamer->name }}</td>
                            <td>
                                <span class="badge {{ $log->was_live ? 'bg-danger' : 'bg-secondary' }}">
                                    {{ $log->was_live ? '直播中' : '未直播' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $log->is_live ? 'bg-danger' : 'bg-secondary' }}">
                                    {{ $log->is_live ? '直播中' : '未直播' }}
                                </span>
                            </td>
                            <td>
                                @if($log->notification_sent)
                                    <span class="badge bg-success">已发送</span>
                                @else
                                    <span class="badge bg-secondary">未发送</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $logs->links() }}
    </div>
</div>
@endsection