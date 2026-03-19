<x-app-layout :role="$role">

    <div class="container">

        <h1 class="admin-title">User Feedback</h1>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Type</th>
                        <th>Rating</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @forelse ($feedback as $item)
                    <tbody>
                        <tr>
    
                            <td>
                                <a href="{{ route('admin.users.show', $item->user) }}">
                                    <x-user-image :user="$item->user" class="my-cars-img-thumbnail" />
                                </a>
                            </td>
    
                            <td>{{ ucfirst($item->type) }}</td>
    
                            <td>{{ $item->rating ?? '-' }}</td>
    
                            <td>
                                <div class="feedback-message" onclick="toggleMessage(this)">
                                    <span class="short-text">
                                        {{ Str::limit($item->message, 80) }}
                                    </span>
    
                                    <span class="full-text">
                                        {{ $item->message }}
                                    </span>
    
                                    <small class="click-hint">Tap to read more</small>
                                </div>
                            </td>
    
                            <td>
    
                                <span class="status-badge status-{{ $item->status }}">
                                    {{ ucfirst($item->status) }}
                                </span>
    
                            </td>
    
                            <td>
    
                                <form method="POST" action="{{ route('admin.feedback.status', $item->id) }}">
    
                                    @csrf
                                    @method('PATCH')
    
                                    <select name="status">
                                        <option value="pending">Pending</option>
                                        <option value="reviewed">Reviewed</option>
                                        <option value="resolved">Resolved</option>
                                        <option value="rejected">Rejected</option>
    
                                    </select>
    
                                    <button class="btn btn-primary" style="margin-top: 10px">Update</button>
    
                                </form>
    
                            </td>
    
                        </tr>
    
                    </tbody>
                @empty
                    <h3>No feedback from Users</h3>
                @endforelse
    
            </table>
        </div>

        {{ $feedback->links() }}

    </div>
    <script>
        function toggleMessage(element) {
            element.classList.toggle('expanded');
        }
    </script>

</x-app-layout>
