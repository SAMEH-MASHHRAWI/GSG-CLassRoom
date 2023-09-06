<?php

namespace App\Notifications;

use App\Models\Classwork;
use App\Notifications\Channels\HadaraSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;

class NewClassworkNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Classwork $classwork)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $via=['database',HadaraSmsChannel::class,'broadcast','mail'];
        // if($notifiable->receive_mail_notifications){
        //     $via []='mail';
        // }
        //     if ($notifiable->receive_mail_notifications) {
        //         $via[] = 'brodcast';
        //     }
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $classwork = $this->classwork;
        $content = __(':name posted a new :type : :title', [
            'name' => $classwork->user->name,
            'type' => __($classwork->type->value),
            'title' => $classwork->title,
        ]);


        return (new MailMessage)
                ->subject(__('New :type',[
                     'type'=>$this->classwork->type->value,
                ]))
                ->greeting(__('Hi :name',[
                    'name'=>$notifiable->name
                ]))
                    ->line($content)
                    ->action(__('Go to Classwork'),route('classrooms.classworks.show',[$classwork->classroom_id,$classwork]))
                    ->line('Thank you for using our application!');
    }
    public function toVonage(object $notifiable)
    {
        return (new VonageMessage)->content (__('A new Classwork'));
    }
        public function toHadara(object $notifiable)
        {
            return __('A new Classwork');
        }
    public function toDatabase(object $notifiable):DatabaseMessage
    {
    //         $classwork = $this->classwork;
    //         $content = __(':name posted a new :type: :title', [
    //             'name' => $this->classwork->user->name,
    //             'type' => __($this->classwork->type),
    //             'title' => $this->classwork->title,
    //         ]);
    //     return new DatabaseMessage([
    //         'title'=> (__('New :type', [
    //                 'type' => $this->classwork->type->value,
    //             ])),

    //         'body'=> $content,
    //         'image'=>'',
    //         'link'=>route('classrooms.classworks.show',[$classwork->classroom_id,$classwork]),
    //         'classwork_id'=>$classwork->id,
    //   ]);
            return new DatabaseMessage($this->createMessage());

    }
    public function tBrodcastMessage(object $notifiable):BroadcastMessage
    {
            // $classwork = $this->classwork;
            // $content = __(':name posted a new :type: :title', [
            //     'name' => $this->classwork->user->name,
            //     'type' => __($this->classwork->type),
            //     'title' => $this->classwork->title,
            // ]);
            // return new BroadcastMessage([
            //     'title' => (__('New :type', [
            //         'type' => $this->classwork->type->value,
            //     ])),

            //     'body' => $content,
            //     'image' => '',
            //     'link' => route('classrooms.classworks.show', [$classwork->classroom_id, $classwork]),
            //     'classwork_id' => $classwork->id,
            // ]);
            return new BroadcastMessage($this->createMessage());
    }
    protected function createMessage(): array
    {
            $classwork = $this->classwork;
            $content = __(':name posted a new :type: :title', [
                'name' => $this->classwork->user->name,
                'type' => __($this->classwork->type),
                'title' => $this->classwork->title,
            ]);
            return new BroadcastMessage([
                'title' => (__('New :type', [
                    'type' => $this->classwork->type->value,
                ])),

                'body' => $content,
                'image' => '',
                'link' => route('classrooms.classworks.show', [$classwork->classroom_id, $classwork]),
                'classwork_id' => $classwork->id,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
