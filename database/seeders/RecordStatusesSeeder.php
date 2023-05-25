<?php

namespace Database\Seeders;

use App\Models\RecordStatus;
use Illuminate\Database\Seeder;
use PhpParser\Node\Stmt\Continue_;

class RecordStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'isDefault' => true,
                'value' => [
                    'ru' => 'в обработке',
                    'en' => 'in progress',
                    'kk' => 'орындалуда',
                ],

                'status' => RecordStatus::SECONDARY
            ],

            [
                'value' => [
                    'ru' => 'Приходите, мы ждём вас!',
                    'en' => 'We are waiting for you!',
                    'kk' => 'Келіңіз, біз сізді күтеміз!',
                ],

                'status' => RecordStatus::SUCCESS
            ],

            [
                'value' => [
                    'ru' => 'В нашем фонде такой литературы нет',
                    'en' => 'There is no such literature in our fund',
                    'kk' => 'Біздің қорда ондай әдебиет жоқ',
                ],

                'status' => RecordStatus::DANGER
            ],

            [
                'value' => [
                    'ru' => 'Книга на руках',
                    'en' => 'Book in hand',
                    'kk' => 'Кітап қолда',
                ],

                'status' => RecordStatus::WARNING
            ],

            [
                'value' => [
                    'ru' => 'Ответ по эл. почте',
                    'en' => 'Reply by email',
                    'kk' => 'Жауап электрондық пошта арқылы',
                ],

                'status' => RecordStatus::DARK
            ]
        ];

        $this->saveStatuses($statuses);
    }

    private function saveStatuses(array $statuses)
    {
        foreach ($statuses as $value) {
            $status = RecordStatus::create([
                'title' => $value['value']['ru'],
                'subject' => $value['value']['ru'],
                'content' => $value['value']['ru'],
                'isDefault' => array_key_exists('isDefault', $value) && $value['isDefault'],
                'badge' => $value['status']
            ]);

            foreach ($value['value'] as $lang => $value) {
                if($lang === 'ru') continue;

                $status = $status->translate($lang);
                $status->title = $value;
                $status->subject = $value;
                $status->content = $value;
                $status->save();
            }
        }
    }
}
