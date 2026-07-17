<?php
$receipts = DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'payment_receipts'");
echo "PAYMENT_RECEIPTS:\n";
echo json_encode($receipts, JSON_PRETTY_PRINT) . "\n";

$settings = DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'tenant_settings' AND column_name IN ('default_district', 'default_seven_twelve_qty', 'pik_vima_default_service_charge')");
echo "TENANT_SETTINGS:\n";
echo json_encode($settings, JSON_PRETTY_PRINT) . "\n";
