<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class PurchaseOrder extends Model {
    use HasFactory;
    protected $fillable = ['po_number', 'quotation_id', 'client_id', 'status', 'subtotal', 'tax_amount', 'grand_total', 'order_date'];
    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function quotation(): BelongsTo { return $this->belongsTo(Quotation::class); }
    public function items(): HasMany { return $this->hasMany(PurchaseOrderItem::class); }
}