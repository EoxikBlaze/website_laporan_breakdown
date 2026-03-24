import * as React from "react";
import { PremiumDataTable, Column } from "@/components/ui/premium-data-table";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Edit2, Trash2, Truck, CheckCircle2, PlayCircle, AlertTriangle } from "lucide-react";

interface MasterUnit {
  id: number;
  nomor_lambung: string;
  jenis_unit: string;
  vendor?: {
    id: number;
    nama_vendor: string;
  };
}

interface MasterUnitTableProps {
  units: MasterUnit[];
  editRoute: string; // Base route for edit: /master_units/{id}/edit
  deleteRoute: string; // Base route for delete: /master_units/{id}
  csrfToken: string;
}

export const MasterUnitTable = ({ units, editRoute, deleteRoute, csrfToken }: MasterUnitTableProps) => {
  const columns: Column[] = [
    {
      key: "nomor_lambung",
      label: "Nomor Lambung",
      render: (val) => (
        <div className="flex items-center gap-2">
          <div className="h-8 w-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
            <Truck className="h-4 w-4" />
          </div>
          <span className="font-bold text-foreground">{val}</span>
        </div>
      )
    },
    {
      key: "jenis_unit",
      label: "Jenis Unit",
    },
    {
      key: "vendor",
      label: "Vendor / Bengkel",
      render: (val) => (
        val ? (
          <div className="flex items-center gap-1.5 text-primary font-medium">
            <div className="h-1.5 w-1.5 rounded-full bg-primary" />
            {val.nama_vendor}
          </div>
        ) : (
          <span className="text-muted-foreground italic text-xs">Tidak ada vendor</span>
        )
      )
    },
    {
      key: "id",
      label: "Aksi",
      render: (id) => (
        <div className="flex items-center gap-2">
          <Button
            variant="ghost"
            size="icon"
            className="h-8 w-8 text-info hover:text-info hover:bg-info/10"
            onClick={() => window.location.href = editRoute.replace(':id', id.toString())}
          >
            <Edit2 className="h-4 w-4" />
          </Button>
          <form
            action={deleteRoute.replace(':id', id.toString())}
            method="POST"
            onSubmit={(e) => {
              if (!confirm('Apakah Anda yakin ingin menghapus unit ini?')) {
                e.preventDefault();
              }
            }}
            className="inline"
          >
            <input type="hidden" name="_token" value={csrfToken} />
            <input type="hidden" name="_method" value="DELETE" />
            <Button
              variant="ghost"
              size="icon"
              type="submit"
              className="h-8 w-8 text-destructive hover:text-destructive hover:bg-destructive/10"
            >
              <Trash2 className="h-4 w-4" />
            </Button>
          </form>
        </div>
      )
    }
  ];

  return (
    <PremiumDataTable
      data={units}
      columns={columns}
      searchPlaceholder="Cari nomor lambung, jenis, or vendor..."
    />
  );
};
