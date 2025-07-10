<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Ventas';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la Orden')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Número de Orden')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),

                        Forms\Components\Select::make('user_id')
                            ->label('Cliente')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'pending' => 'Pendiente',
                                'processing' => 'Procesando',
                                'shipped' => 'Enviado',
                                'delivered' => 'Entregado',
                                'cancelled' => 'Cancelado',
                            ])
                            ->required(),

                        Forms\Components\Select::make('payment_status')
                            ->label('Estado de Pago')
                            ->options([
                                'pending' => 'Pendiente',
                                'paid' => 'Pagado',
                                'failed' => 'Fallido',
                                'refunded' => 'Reembolsado',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal (centavos)')
                            ->required()
                            ->numeric()
                            ->minValue(0),

                        Forms\Components\TextInput::make('tax_amount')
                            ->label('Impuestos (centavos)')
                            ->required()
                            ->numeric()
                            ->minValue(0),

                        Forms\Components\TextInput::make('shipping_amount')
                            ->label('Envío (centavos)')
                            ->required()
                            ->numeric()
                            ->minValue(0),

                        Forms\Components\TextInput::make('total')
                            ->label('Total (centavos)')
                            ->required()
                            ->numeric()
                            ->minValue(0),

                        Forms\Components\TextInput::make('mercadopago_payment_id')
                            ->label('ID Pago Mercado Pago')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('mercadopago_preference_id')
                            ->label('ID Preferencia Mercado Pago')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Dirección de Envío')
                    ->schema([
                        Forms\Components\TextInput::make('shipping_address.name')
                            ->label('Nombre')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('shipping_address.email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('shipping_address.phone')
                            ->label('Teléfono')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('shipping_address.address')
                            ->label('Dirección')
                            ->rows(2)
                            ->maxLength(500),

                        Forms\Components\TextInput::make('shipping_address.city')
                            ->label('Ciudad')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('shipping_address.state')
                            ->label('Provincia')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('shipping_address.postal_code')
                            ->label('Código Postal')
                            ->maxLength(20),
                    ])->columns(2),

                Forms\Components\Section::make('Fechas')
                    ->schema([
                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Fecha de Pago'),

                        Forms\Components\DateTimePicker::make('shipped_at')
                            ->label('Fecha de Envío'),

                        Forms\Components\DateTimePicker::make('delivered_at')
                            ->label('Fecha de Entrega'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Número')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('ARS')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'primary' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'processing' => 'Procesando',
                        'shipped' => 'Enviado',
                        'delivered' => 'Entregado',
                        'cancelled' => 'Cancelado',
                        default => $state,
                    }),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Pago')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'info' => 'refunded',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado',
                        'failed' => 'Fallido',
                        'refunded' => 'Reembolsado',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'processing' => 'Procesando',
                        'shipped' => 'Enviado',
                        'delivered' => 'Entregado',
                        'cancelled' => 'Cancelado',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Estado de Pago')
                    ->options([
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado',
                        'failed' => 'Fallido',
                        'refunded' => 'Reembolsado',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
} 