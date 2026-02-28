<?php

namespace App\Jobs;


use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
class ExportOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Order $order;
    protected string $status;


    public function __construct(Order $order,string $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    public function handle(): void
    {
        $this->order->update(['status' => $this->status]);
        $url = config('services.export_order_url');

        $response = Http::retry(3, 2000)->timeout(2600)->connectTimeout(2600)->post($url, [
            'order_id' => $this->order->id,
            'status' => $this->status,
        ]);

        if ($response->successful()) {
            Log::info('Export order successful', ['order_id' => $this->order->id]);
        } else {
            Log::error('Export order failed', ['order_id' => $this->order->id, 'response' => $response->body()]);
        }
    }
}
