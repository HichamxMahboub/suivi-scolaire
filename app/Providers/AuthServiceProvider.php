use App\Models\Message;
use App\Policies\MessagePolicy;

    protected $policies = [
        Message::class => MessagePolicy::class,
    ]; 