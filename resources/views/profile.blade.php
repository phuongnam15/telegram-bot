@extends("layouts.main")

@section("title", "Profile")

@section("content")
<div class="container relative mx-auto rounded bg-white dark:bg-[#1a222d] p-5">
    <form action="{{ url("/api/admin") }}" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
        @csrf
        <div class="mb-4 flex-1">
            <input type="text" class="text-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 font-popi text-sm form-input w-full rounded border outline-none pl-3 py-2" id="profile_name" name="name" required />
        </div>
        <div class="mb-4 flex-1">
            <input type="text" class="text-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 font-popi text-sm form-input w-full rounded border outline-none pl-3 py-2" id="profile_email" name="email" required  />
        </div>
        <div class="mb-4 flex-1">
            <input type="text" class="text-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 font-popi text-sm form-input w-full rounded border outline-none pl-3 py-2" id="profile_telegram_id" name="telegramId" required placeholder="set telegram id here | eg: 61487557834"/>
        </div>
        <button id="updateProfileButton" type="submit" class="rounded bg-gray-600 px-3 border border-gray-600 py-2 text-white text-sm">
            Save
        </button>
    </form>
</div>
@endsection
@push("scripts")
<script>
    const updateProfile = async () => {
        try {
            const formData = new FormData();
            formData.append("name", document.getElementById('profile_name').value);
            formData.append("email", document.getElementById('profile_email').value);
            formData.append("telegram_id", document.getElementById('profile_telegram_id').value);

            const response = await fetchClient('/api/admin', {
                method: "POST",
                body: formData
            });

            showNotification('Update successfully', 'success');
            window.location.reload();
        } catch (error) {
            console.log(error);
        }
    }
    $('#updateProfileButton').on('click', async (e) => {
        e.preventDefault();
        await updateProfile();
    });
</script>
@endpush