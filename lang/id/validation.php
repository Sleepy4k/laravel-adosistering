<?php

return [

    /*
    |---------------------------------------------------------------------------------------
    |   Baris Bahasa Untuk Validasi
    |---------------------------------------------------------------------------------------
    |
    |   Baris bahasa berikut ini berisi standar pesan kesalahan yang digunakan oleh
    |   kelas validasi. Beberapa aturan mempunyai banyak versi seperti aturan 'size'.
    |   Jangan ragu untuk mengoptimalkan setiap pesan yang ada di sini.
    |
    */

    'accepted'          => ':attribute harus diterima.',
    'accepted_if'       => ':attribute harus diterima bila :other adalah :value.',
    'active_url'        => ':attribute bukan URL yang valid.',
    'after'             => ':attribute harus berisi tanggal setelah :date.',
    'after_or_equal'    => ':attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha'             => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'        => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'         => ':attribute hanya boleh berisi huruf dan angka.',
    'any_of'            => ':attribute harus salah satu dari berikut: :values.',
    'array'             => ':attribute harus berisi sebuah array.',
    'ascii'             => ':attribute harus berisi karakter ASCII.',
    'before'            => ':attribute harus berisi tanggal sebelum :date.',
    'before_or_equal'   => ':attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between'           => [
        'array'   => ':attribute harus memiliki :min sampai :max anggota.',
        'file'    => ':attribute harus berukuran antara :min sampai :max kilobita.',
        'numeric' => ':attribute harus bernilai antara :min sampai :max.',
        'string'  => ':attribute harus berisi antara :min sampai :max karakter.',
    ],
    'boolean'           => ':attribute harus bernilai true atau false',
    'can'               => 'Anda tidak memiliki izin untuk mengakses :attribute.',
    'confirmed'         => 'Konfirmasi :attribute tidak cocok.',
    'contains'          => ':attribute harus mengandung salah satu dari berikut: :values',
    'current_password'  => 'Kata sandi salah.',
    'date'              => ':attribute bukan tanggal yang valid.',
    'date_equals'       => ':attribute harus berisi tanggal yang sama dengan :date.',
    'date_format'       => ':attribute tidak cocok dengan format :format.',
    'decimal'           => ':attribute harus berupa angka desimal.',
    'declined'          => ':attribute harus ditolak.',
    'declined_if'       => ':attribute harus ditolak bila :other adalah :value.',
    'different'         => ':attribute dan :other harus berbeda.',
    'digits'            => ':attribute harus terdiri dari :digits angka.',
    'digits_between'    => ':attribute harus terdiri dari :min sampai :max angka.',
    'dimensions'        => ':attribute tidak memiliki dimensi gambar yang valid.',
    'distinct'          => ':attribute memiliki nilai yang duplikat.',
    'doesnt_end_with'   => ':attribute tidak boleh diakhiri salah satu dari berikut: :values',
    'doesnt_start_with' => ':attribute tidak boleh diawali salah satu dari berikut: :values',
    'email'             => ':attribute harus berupa alamat surel yang valid.',
    'ends_with'         => ':attribute harus diakhiri salah satu dari berikut: :values',
    'enum'              => ':attribute harus salah satu dari: :values.',
    'exists'            => ':attribute yang dipilih tidak valid.',
    'extension'         => ':attribute harus berkas berjenis: :values.',
    'file'              => ':attribute harus berupa sebuah berkas.',
    'filled'            => ':attribute harus memiliki nilai.',
    'gt'                => [
        'array'   => ':attribute harus memiliki lebih dari :value anggota.',
        'file'    => ':attribute harus berukuran lebih besar dari :value kilobita.',
        'numeric' => ':attribute harus bernilai lebih besar dari :value.',
        'string'  => ':attribute harus berisi lebih besar dari :value karakter.',
    ],
    'gte'               => [
        'array'   => ':attribute harus terdiri dari :value anggota atau lebih.',
        'file'    => ':attribute harus berukuran lebih besar dari atau sama dengan :value kilobita.',
        'numeric' => ':attribute harus bernilai lebih besar dari atau sama dengan :value.',
        'string'  => ':attribute harus berisi lebih besar dari atau sama dengan :value karakter.',
    ],
    'hex_color'         => ':attribute harus berupa format warna heksadesimal.',
    'image'             => ':attribute harus berupa gambar.',
    'in'                => ':attribute yang dipilih tidak valid.',
    'in_array'          => ':attribute tidak ada di dalam :other.',
    'in_array_keys'     => ':attribute harus memiliki kunci yang valid di dalam :other.',
    'integer'           => ':attribute harus berupa bilangan bulat.',
    'ip'                => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'              => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'              => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'              => ':attribute harus berupa JSON string yang valid.',
    'list'              => ':attribute harus berupa daftar.',
    'lowercase'         => ':attribute harus berisi huruf kecil.',
    'lt'                => [
        'array'   => ':attribute harus memiliki kurang dari :value anggota.',
        'file'    => ':attribute harus berukuran kurang dari :value kilobita.',
        'numeric' => ':attribute harus bernilai kurang dari :value.',
        'string'  => ':attribute harus berisi kurang dari :value karakter.',
    ],
    'lte'               => [
        'array'   => ':attribute harus tidak lebih dari :value anggota.',
        'file'    => ':attribute harus berukuran kurang dari atau sama dengan :value kilobita.',
        'numeric' => ':attribute harus bernilai kurang dari atau sama dengan :value.',
        'string'  => ':attribute harus berisi kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address'       => ':attribute harus berupa alamat MAC yang valid.',
    'max'               => [
        'array'   => ':attribute maksimal terdiri dari :max anggota.',
        'file'    => ':attribute maksimal berukuran :max kilobytes.',
        'numeric' => ':attribute maskimal bernilai :max.',
        'string'  => ':attribute maskimal berisi :max karakter.',
    ],
    'max_digits'        => ':attribute tidak boleh lebih dari :max digit.',
    'mimes'             => ':attribute harus berupa berkas berjenis: :values.',
    'mimetypes'         => ':attribute harus berupa berkas berjenis: :values.',
    'min'               => [
        'array'   => ':attribute minimal terdiri dari :min anggota.',
        'file'    => ':attribute minimal berukuran :min kilobita.',
        'numeric' => ':attribute minimal bernilai :min.',
        'string'  => ':attribute minimal berisi :min karakter.',
    ],
    'min_digits'            => ':attribute harus minimal :min digit.',
    'missing'               => ':attribute harus ada.',
    'missing_if'            => ':attribute harus ada bila :other adalah :value.',
    'missing_unless'        => ':attribute harus ada kecuali :other adalah :values.',
    'missing_with'          => ':attribute harus ada bila :values ada.',
    'missing_with_all'      => ':attribute harus ada bila :values ada.',
    'multiple_of'           => ':attribute harus merupakan kelipatan dari :value.',
    'not_in'                => ':attribute yang dipilih tidak valid.',
    'not_regex'             => 'Format :attribute tidak valid.',
    'numeric'               => ':attribute harus berupa angka.',
    'password'              => [
        'letters' => 'Kata sandi minimal mengandung satu huruf.',
        'mixed'  => 'Kata sandi minimal mengandung huruf dan angka.',
        'numbers' => 'Kata sandi minimal mengandung satu angka.',
        'symbols' => 'Kata sandi minimal mengandung satu simbol.',
        'uncompromised' => 'Kata sandi tidak boleh digunakan karena telah terungkap dalam pelanggaran data sebelumnya.',
    ],
    'present'               => ':attribute wajib ada.',
    'present_if'            => ':attribute wajib ada bila :other adalah :value.',
    'present_unless'        => ':attribute wajib ada kecuali :other adalah :values.',
    'present_with'          => ':attribute wajib ada bila terdapat :values.',
    'present_with_all'      => ':attribute wajib ada bila terdapat :values.',
    'prohibited'            => ':attribute dilarang.',
    'prohibited_if'         => ':attribute dilarang bila :other adalah :value.',
    'prohibited_if_accepted'=> ':attribute dilarang bila :other diterima.',
    'prohibited_if_declined'=> ':attribute dilarang bila :other ditolak.',
    'prohibited_unless'     => ':attribute dilarang kecuali :other adalah :values.',
    'prohibits'             => ':attribute melarang :other untuk ada.',
    'regex'                 => 'Format :attribute tidak valid.',
    'required'              => ':attribute wajib diisi.',
    'required_array_keys'   => 'Semua kunci dalam :attribute harus diisi.',
    'required_if'           => ':attribute wajib diisi bila :other adalah :value.',
    'required_if_accepted'  => ':attribute wajib diisi bila :other diterima.',
    'required_if_declined'  => ':attribute wajib diisi bila :other ditolak.',
    'required_unless'       => ':attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with'         => ':attribute wajib diisi bila terdapat :values.',
    'required_with_all'     => ':attribute wajib diisi bila terdapat :values.',
    'required_without'      => ':attribute wajib diisi bila tidak terdapat :values.',
    'required_without_all'  => ':attribute wajib diisi bila sama sekali tidak terdapat :values.',
    'same'                  => ':attribute dan :other harus sama.',
    'size'                  => [
        'array'   => ':attribute harus mengandung :size anggota.',
        'file'    => ':attribute harus berukuran :size kilobyte.',
        'numeric' => ':attribute harus berukuran :size.',
        'string'  => ':attribute harus berukuran :size karakter.',
    ],
    'starts_with' => ':attribute harus diawali salah satu dari berikut: :values',
    'string'      => ':attribute harus berupa string.',
    'timezone'    => ':attribute harus berisi zona waktu yang valid.',
    'unique'      => ':attribute sudah ada sebelumnya.',
    'uploaded'    => ':attribute gagal diunggah.',
    'uppercase'   => ':attribute harus berisi huruf kapital.',
    'url'         => 'Format :attribute tidak valid.',
    'ulid'        => ':attribute harus merupakan ULID yang valid.',
    'uuid'        => ':attribute harus merupakan UUID yang valid.',

    /*
    |---------------------------------------------------------------------------------------
    | Baris Bahasa untuk Validasi Kustom
    |---------------------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan pesan validasi untuk atribut sesuai keinginan dengan menggunakan
    | konvensi "attribute.rule" dalam penamaan barisnya. Hal ini mempercepat dalam menentukan
    | baris bahasa kustom yang spesifik untuk aturan atribut yang diberikan.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message'
        ],
    ],

    /*
    |---------------------------------------------------------------------------------------
    | Kustom Validasi Atribut
    |---------------------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan untuk menukar 'placeholder' atribut dengan sesuatu yang
    | lebih mudah dimengerti oleh pembaca seperti "Alamat Surel" daripada "surel" saja.
    | Hal ini membantu kita dalam membuat pesan menjadi lebih ekspresif.
    |
    */

    'attributes' => []

];
