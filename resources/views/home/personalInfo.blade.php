<x-app-layout :role="$role">
    {{-- Messages --}}
    <x-success-message />
    <x-error-message />

    <div class="container py-4">
        <div class="card shadow">
            <div class="card-header">
                <h4>Personal Information</h4>
            </div>

            <div class="card-body">

                {{-- Profile Image --}}
                <div class="text-center mb-4">
                    <x-user-image :user="$user" class="rounded-circle border profile-img"/>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="mb-medium">State</label>
                            <select name="state_id" id="stateSelect" class="form-control">
                                <option value="">Select State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" @selected(old('state_id', $user->state_id) == $state->id)>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state_id')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>City</label>
                            <select name="city_id" id="citySelect" class="form-control">
                                <option value="">Select City</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ old('city', $user->city_id) == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Address</label>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}"
                                class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Profile Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                    </div>

                    <button type="submit" class="btn btn-profile">
                        Update Profile
                    </button>

                </form>

            </div>
        </div>
    </div>

</x-app-layout>
