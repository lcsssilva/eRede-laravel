<?php

namespace Lcs13761\EredeLaravel\Enums;

enum EndpointType: string
{
    case AUTHORIZATION = 'authorization';
    case TOKENIZATION = 'tokenization';
}