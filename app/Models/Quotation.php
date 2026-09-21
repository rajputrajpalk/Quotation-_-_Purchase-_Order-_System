<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Quotation extends Model {
    use HasFactory;
    protected $fillable = ['quotation_number', 'client_id', 'status', 'subtotal', 'tax_amount', 'grand_total', 'valid_until'];
    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function items(): HasMany { return $this->hasMany(QuotationItem::class); }
}