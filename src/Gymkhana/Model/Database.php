<?php

namespace Gymkhana\Model;

class Database
{
    public function loadData(): array
    {
        return [
            ['id'=> 1, 'class' => 'A', 'name' => 'Ходотович Денис', 'vehicle' => 'Kawasaki Z 750'],
            ['id'=> 2, 'class' => 'C', 'name' => 'Ковальов Максим', 'vehicle' => 'Yamaha MT 07'],
            ['id'=> 3, 'class' => 'A', 'name' => 'Лисюк Олег', 'vehicle' => 'Honda Hornet 900'],
            ['id'=> 7, 'class' => 'B', 'name' => 'Лисенко Дмитро', 'vehicle' => 'Honda CB 400sf'],
            ['id'=> 8, 'class' => 'C', 'name' => 'Чеберяка Роман', 'vehicle' => 'Yamaha XTZ 660 Tenere'],
            ['id'=> 9, 'class' => 'B', 'name' => 'Верстюк Богдан', 'vehicle' => 'Honda Transalp 650'],
            ['id'=> 4, 'class' => 'C', 'name' => 'Куницын Василий', 'vehicle' => 'Aprilia Pegaso 650 ie'],
            ['id'=> 5, 'class' => 'A', 'name' => 'Кателло Александр', 'vehicle' => 'Honda Shadow VT 1100'],
            ['id'=> 6, 'class' => 'B', 'name' => 'Гранн Ернест', 'vehicle' => 'Suzuki Intruder 400'],
            ['id'=> 10, 'class' => 'L', 'name' => 'Руденко Тетяна', 'vehicle' => 'Suzuki Intruder M800']
        ];
    }
}
