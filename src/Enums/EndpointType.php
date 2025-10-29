<?php

namespace Lcsssilva\EredeLaravel\Enums;

enum EndpointType: string
{
    case AUTHORIZATION = 'authorization';
    case TOKENIZATION = 'tokenization';
}