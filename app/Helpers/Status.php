<?php

namespace App\Helpers;

use InvalidArgumentException;

class Status
{
    const STATUS_WAITING_FOR_PAYMENT = 1;  // Default
    const STATUS_PAYMENT_ERROR = 2;
    const STATUS_CONFIRMED = 3;
    const STATUS_FULFILLED = 4;          // All status value less than this consider as unfulfilled
    const STATUS_AWAITING_DELIVERY = 5;
    const STATUS_DELIVERED = 6;
    const STATUS_RETURNED = 7;
    const STATUS_CANCELED = 8;
    const STATUS_DISPUTED = 9;
    const STATUS_PACKED = 10;

    const PAYMENT_STATUS_UNPAID = 1;       // Default
    const PAYMENT_STATUS_PENDING = 2;
    const PAYMENT_STATUS_PAID = 3;        // All status before paid value consider as unpaid
    const PAYMENT_STATUS_INITIATED_REFUND = 4;
    const PAYMENT_STATUS_PARTIALLY_REFUNDED = 5;
    const PAYMENT_STATUS_REFUNDED = 6;

    const FULFILMENT_TYPE_DELIVER = 'deliver';
    const FULFILMENT_TYPE_PICKUP = 'pickup';

	public static function getStatusCode(string $statusString): int
    {
        $allStatuses = array_merge(
            [
                'STATUS_WAITING_FOR_PAYMENT' => self::STATUS_WAITING_FOR_PAYMENT,
                'STATUS_PAYMENT_ERROR' => self::STATUS_PAYMENT_ERROR,
                'STATUS_CONFIRMED' => self::STATUS_CONFIRMED,
                'STATUS_FULFILLED' => self::STATUS_FULFILLED,
                'STATUS_AWAITING_DELIVERY' => self::STATUS_AWAITING_DELIVERY,
                'STATUS_DELIVERED' => self::STATUS_DELIVERED,
                'STATUS_RETURNED' => self::STATUS_RETURNED,
                'STATUS_CANCELED' => self::STATUS_CANCELED,
                'STATUS_DISPUTED' => self::STATUS_DISPUTED,
                'STATUS_PACKED' => self::STATUS_PACKED,
            ],
            [
                'PAYMENT_STATUS_UNPAID' => self::PAYMENT_STATUS_UNPAID,
                'PAYMENT_STATUS_PENDING' => self::PAYMENT_STATUS_PENDING,
                'PAYMENT_STATUS_PAID' => self::PAYMENT_STATUS_PAID,
                'PAYMENT_STATUS_INITIATED_REFUND' => self::PAYMENT_STATUS_INITIATED_REFUND,
                'PAYMENT_STATUS_PARTIALLY_REFUNDED' => self::PAYMENT_STATUS_PARTIALLY_REFUNDED,
                'PAYMENT_STATUS_REFUNDED' => self::PAYMENT_STATUS_REFUNDED,
            ]
        );

        if (array_key_exists($statusString, $allStatuses)) {
            return $allStatuses[$statusString];
        }

        throw new InvalidArgumentException("Invalid status string: $statusString");
    }

    public static function isFulfilled(int $status): bool
    {
        return $status >= self::STATUS_FULFILLED;
    }

    public static function isPaid(int $paymentStatus): bool
    {
        return $paymentStatus >= self::PAYMENT_STATUS_PAID;
    }
}
