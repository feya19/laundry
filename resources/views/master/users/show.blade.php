<table class="table">
    <tr>
        <th style="border: 0; width: 120px">Username</th>
        <td style="border: 0; width: 1px">:</td>
        <td style="border: 0;">{{ $model->username }}</td>
    </tr>
    <tr>
        <th style="border: 0; width: 120px">Nama</th>
        <td style="border: 0; width: 1px">:</td>
        <td style="border: 0;">{{ $model->name }}</td>
    </tr>
    <tr>
        <th style="border: 0; width: 120px">Role</th>
        <td style="border: 0; width: 1px">:</td>
        <td style="border: 0;">{{ $model->role }}</td>
    </tr>
    <tr>
        <th style="border: 0; width: 120px">Foto</th>
        <td style="border: 0; width: 1px">:</td>
        <td style="border: 0;">
        @php
        $alt_img = '<div class="avatar bg-purple widget13-avatar">
                        <div class="avatar-display">
                            <i class="fa fa-user-alt"></i>
                        </div>
                    </div>';
        if($model->photo){
            $path = "upload/profile/".$model->photo;
            if(file_exists(public_path($path))){
                echo '<img src="'.asset($path).'" alt="" style="max-height: 100px;">';
            }else{
                echo $alt_img;
            }
        }else{
            echo $alt_img;
        }
        @endphp   
        </td>
    </tr>
</table>