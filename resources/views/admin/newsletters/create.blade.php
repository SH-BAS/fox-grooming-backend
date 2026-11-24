@extends('layouts.admin')

@section('title', 'Send Newsletter')

@section('content')
    <style>
        .premium-panel {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background:
                linear-gradient(145deg, rgba(255, 0, 0, 0.12) 0%, rgba(0, 0, 0, 0.3) 40%, rgba(18, 18, 18, 0.98) 100%),
                linear-gradient(180deg, #1a1a1a 0%, #111 100%);
            border: 1px solid rgba(255, 0, 0, 0.22);
            border-left: 3px solid #ff0000;
            border-radius: 16px;
            box-shadow:
                0 12px 30px rgba(0, 0, 0, 0.4),
                inset 0 0 40px rgba(255, 0, 0, 0.04);
        }

        .premium-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 0, 0, 0.16), transparent 55%);
            pointer-events: none;
            z-index: -1;
        }

        .premium-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 22px 24px 0;
        }

        .premium-header h4 {
            margin: 0;
            color: #fff;
            font-weight: 700;
        }

        .premium-body {
            padding: 22px 24px 24px;
        }

        .premium-panel .form-label {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .premium-panel .form-control {
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #fff;
            border-radius: 10px;
            min-height: 44px;
        }

        .premium-panel .form-control:focus {
            background: rgba(0, 0, 0, 0.45);
            border-color: rgba(255, 0, 0, 0.55);
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.15);
            color: #fff;
        }

        .premium-panel .form-control::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        .premium-hint {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 0 0 18px;
            padding: 12px 14px;
            border-radius: 12px;
            background: rgba(230, 57, 70, 0.12);
            border: 1px solid rgba(255, 0, 0, 0.22);
            color: rgba(255, 255, 255, 0.75);
            font-size: 13px;
        }

        .premium-hint i {
            color: #ff4d5a;
            margin-top: 2px;
        }

        .premium-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        .premium-actions .btn {
            border-radius: 10px;
            min-width: 160px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }

        .ck.ck-editor {
            border-radius: 10px;
            overflow: hidden;
        }

        .ck.ck-toolbar {
            background: #1a1a1a !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        .ck.ck-editor__main > .ck-editor__editable {
            background: rgba(0, 0, 0, 0.35) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: #fff !important;
            min-height: 360px;
        }

        .ck.ck-button,
        .ck.ck-dropdown__button {
            color: rgba(255, 255, 255, 0.8) !important;
        }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="premium-panel">
                <div class="premium-header">
                    <h4>Send Newsletter</h4>
                    <a href="{{ route('admin.newsletters.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
                <div class="premium-body">
                    <div class="premium-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>
                            This newsletter will be queued to <strong style="color:#fff;">{{ $activeCount }}</strong> active
                            subscriber{{ $activeCount === 1 ? '' : 's' }}. Emails are processed in the background.
                        </span>
                    </div>

                    <form action="{{ route('admin.newsletters.send') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Newsletter Title / Subject</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                placeholder="e.g., Weekly Grooming Tips" required>
                            @error('title')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Newsletter Content</label>
                            <textarea name="content" id="editor" class="form-control">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="premium-actions">
                            <button type="submit" class="btn btn-primary" {{ $activeCount < 1 ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane me-1"></i> Send Newsletter Now
                            </button>
                            <a href="{{ route('admin.newsletters.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
