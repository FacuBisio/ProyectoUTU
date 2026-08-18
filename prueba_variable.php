<?php

$apiKey = getenv("OPENAI_API_KEY");

if ($apiKey === false || $apiKey === "") {
    echo "❌ PHP NO encuentra OPENAI_API_KEY";
} else {
    echo "✅ PHP encuentra OPENAI_API_KEY";
}