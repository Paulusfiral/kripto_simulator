"""
Modul Matrix Formatter
Kripto Simulator 2026 | Josua Reynold Tampubolon
Fungsi: format state matrix 4x4 untuk visualisasi ChaCha20
"""

from typing import List


def format_matrix_4x4(state: List[int]) -> str:
    """
    Format list 16 integer menjadi tampilan matrix 4x4 yang rapi.
    Digunakan untuk visualisasi state matrix ChaCha20 (seperti di Slide 11).

    Args:
        state : List berisi 16 integer (32-bit words)

    Returns:
        String matrix 4x4 yang siap ditampilkan

    Contoh output:
        0x61707865  0x3320646e  0x79622d32  0x6b206574
        0x00000001  0x00000002  0x00000003  0x00000004
        ...
    """
    if len(state) != 16:
        raise ValueError(f"State harus berisi 16 elemen, dapat {len(state)}")

    rows = []
    for row_idx in range(4):
        row_vals = []
        for col_idx in range(4):
            val = state[row_idx * 4 + col_idx]
            # Format sebagai hex 8 digit dengan prefix 0x
            row_vals.append(f"0x{val & 0xFFFFFFFF:08x}")
        rows.append("  ".join(row_vals))

    return "\n".join(rows)


def format_matrix_4x4_json(state: List[int]) -> List[List[str]]:
    """
    Format state matrix sebagai nested list (untuk response JSON ke frontend).
    Judika bisa pakai ini untuk render tabel di UI.

    Returns:
        List 4x4 berisi string hex

    Contoh:
        [
          ["0x61707865", "0x3320646e", "0x79622d32", "0x6b206574"],
          ...
        ]
    """
    if len(state) != 16:
        raise ValueError(f"State harus berisi 16 elemen, dapat {len(state)}")

    matrix = []
    for row_idx in range(4):
        row = []
        for col_idx in range(4):
            val = state[row_idx * 4 + col_idx]
            row.append(f"0x{val & 0xFFFFFFFF:08x}")
        matrix.append(row)
    return matrix


# ── Quick test ────────────────────────────────────────────
if __name__ == "__main__":
    # Contoh state matrix dummy (16 angka)
    dummy_state = [
        0x61707865, 0x3320646e, 0x79622d32, 0x6b206574,
        0x00000001, 0x00000002, 0x00000003, 0x00000004,
        0x00000005, 0x00000006, 0x00000007, 0x00000008,
        0x00000009, 0x0000000a, 0x0000000b, 0x0000000c,
    ]

    print("=== State Matrix 4x4 ===")
    print(format_matrix_4x4(dummy_state))
    print()
    print("=== JSON Format ===")
    for row in format_matrix_4x4_json(dummy_state):
        print(row)