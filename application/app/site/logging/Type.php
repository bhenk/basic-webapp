<?php

namespace app\site\logging;

enum Type {
    case stdout;
    case stderr;
    case default;
}
