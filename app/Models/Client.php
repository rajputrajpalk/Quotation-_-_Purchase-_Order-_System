<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Client extends Model {
    use HasFactory;
    protected $fillable = ['name', 'email', 'phone', 'company_name', 'address'];
    public function quotations(): HasMany { return $this->hasMany(Quotation::class); }
    public function purchaseOrders(): HasMany { return $this->hasMany(PurchaseOrder::class); }
}