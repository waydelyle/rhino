@extends('layouts.main')

@section('content')
    <div class="clients">
        <table style="width:100%">
            <tr>
                <th>Name</th>
                <th>Publication</th>
                <th>Emails</th>
                <th>Contact Numbers</th>
                <th>Join Date</th>
            </tr>
            @foreach($clients as $client)
                <tr>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->publication }}</td>
                    <td>
                        <ul>
                            @forelse($client->emails as $email)
                                <li>
                                    @if($email->valid)
                                        <span>&checkmark;</span>
                                    @endif
                                    {{ $email->email }}
                                </li>
                            @empty
                                N/A
                            @endforelse
                        </ul>
                    </td>
                    <td>
                        <ul>
                            @forelse($client->phone_numbers as $number)
                                <li>{{ $number }}</li>
                            @empty
                                N/A
                            @endforelse
                        </ul>
                    </td>
                    <td>{{ $client->join_date }}</td>
                </tr>
            @endforeach
        </table>

        {{ $clients->links() }}
    </div>
@endsection