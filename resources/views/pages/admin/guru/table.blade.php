<h3 style="text-align: center; margin-bottom: 20px;">{{ $title }}</h3>

<table id="myTable" class="display expandable-table" style="width:100%; border-collapse: collapse;">
    <thead style="background-color: #d3d3d3;">
        <tr>
            <th style="text-align: center; border: 1px solid black;">No</th>
            <th style="border: 1px solid black;">Nama</th>
            <th style="border: 1px solid black;">Username</th>
            <th style="border: 1px solid black;">Email</th>
            <th style="border: 1px solid black;">Password</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td style="text-align: center; border: 1px solid black;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid black;">{{ $user->name }}</td>
                <td style="border: 1px solid black;">{{ $user->username }}</td>
                <td style="border: 1px solid black;">{{ $user->email }}</td>
                <td style="border: 1px solid black;">{{ $user->password_plain ?? 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
