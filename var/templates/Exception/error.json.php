<?php
/** @var array $context */

echo json_encode(['status_code' => $context['statusCode'], 'success' => false, 'error' => $context['statusText'], 'data' => []], JSON_THROW_ON_ERROR);
