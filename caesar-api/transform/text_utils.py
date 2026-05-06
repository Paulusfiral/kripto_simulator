"""
Modul Text Utilities
Kripto Simulator 2026 | Josua Reynold Tampubolon
Fungsi: normalisasi teks dan pemecahan blok
"""

import unicodedata
from typing import List


def normalize_text(text: str) -> str:
    """
    Bersihkan dan normalisasi input teks.
    - Hapus whitespace di awal/akhir
    - Normalisasi unicode (NFC)
    - Ganti newline & tab dengan spasi

    Contoh:
        normalize_text("  Hello\n  ") -> "Hello"
        normalize_text("café") -> "café"  (unicode normalized)
    """
    # Normalisasi unicode ke NFC
    text = unicodedata.normalize('NFC', text)
    # Ganti newline dan tab dengan spasi
    text = text.replace('\n', ' ').replace('\t', ' ')
    # Hapus whitespace di awal dan akhir
    text = text.strip()
    return text


def text_to_blocks(text: str, block_size: int = 64) -> List[str]:
    """
    Pecah teks menjadi blok-blok dengan ukuran tertentu.
    Berguna untuk memproses teks panjang (multi-block processing).

    Args:
        text       : Teks yang akan dipecah
        block_size : Ukuran tiap blok dalam karakter (default: 64)

    Returns:
        List berisi potongan-potongan teks

    Contoh:
        text_to_blocks("ABCDEFGH", block_size=3) -> ["ABC", "DEF", "GH"]
    """
    if block_size <= 0:
        raise ValueError("block_size harus lebih besar dari 0")

    blocks = []
    for i in range(0, len(text), block_size):
        blocks.append(text[i:i + block_size])
    return blocks


def count_chars(text: str) -> dict:
    """
    Hitung statistik karakter dalam teks.
    Berguna untuk keperluan edukasi / visualisasi di UI.

    Returns:
        Dict berisi: total, letters, digits, spaces, others
    """
    return {
        "total"  : len(text),
        "letters": sum(1 for c in text if c.isalpha()),
        "digits" : sum(1 for c in text if c.isdigit()),
        "spaces" : sum(1 for c in text if c == ' '),
        "others" : sum(1 for c in text if not c.isalpha() and not c.isdigit() and c != ' '),
    }


# ── Quick test ────────────────────────────────────────────
if __name__ == "__main__":
    raw = "  Hello, World!\n  "
    normed = normalize_text(raw)
    blocks = text_to_blocks(normed, block_size=5)
    stats  = count_chars(normed)

    print(f"Raw     : {repr(raw)}")
    print(f"Normed  : {repr(normed)}")
    print(f"Blocks  : {blocks}")
    print(f"Stats   : {stats}")