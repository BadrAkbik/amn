<!DOCTYPE html>
<html>

<body>
    <div class="grid grid-cols-3 gap-4">
        @foreach ($attachments as $attachment)
            <div class="p-4 bg-white rounded shadow">
                @if (Str::endsWith($attachment, ['.png', '.jpg', '.jpeg', '.gif']))
                    <img src="{{ Storage::disk('public')->url($attachment) }}" class="w-full h-auto rounded"
                        alt="Attachment">
                @elseif (Str::endsWith($attachment, ['.mp4', '.avi', '.mpeg', '.mov']))
                    <video controls class="w-full h-auto rounded">
                        <source src="{{ Storage::disk('public')->url($attachment) }}" type="video/mp4">
                    </video>
                @endif
            </div>
        @endforeach
    </div>
</body>

</html>
