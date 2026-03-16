import * as React from "react";
import { PremiumDataTable, Column } from "@/Components/ui/premium-data-table";
import { Button } from "@/Components/ui/button";
import { Edit2, Trash2, Building2, Phone, FileText } from "lucide-react";

interface Vendor {
  id: number;
  nama_vendor: string;
  kontak: string | null;
  keterangan: string | null;
}

interface VendorTableProps {
  vendors: Vendor[];
  routes: {
    edit: string;
    destroy: string;
  };
  csrfToken: string;
}

export const VendorTable = ({ vendors, routes, csrfToken }: VendorTableProps) => {
  const columns: Column[] = [
    {
      key: "nama_vendor",
      label: "Nama Vendor",
      render: (val) => (
        <div className="flex items-center gap-2">
          <div className="h-8 w-8 rounded-full bg-info/10 flex items-center justify-center text-info">
            <Building2 className="h-4 w-4" />
          </div>
          <span className="font-bold text-foreground">{val}</span>
        </div>
      )
    },
    {
      key: "kontak",
      label: "Kontak",
      render: (val) => (
        <div className="flex items-center gap-2 text-muted-foreground text-xs">
          <Phone className="h-3 w-3" />
          {val || '-'}
        </div>
      )
    },
    {
      key: "keterangan",
      label: "Keterangan",
      render: (val) => (
        <div className="flex items-center gap-2 text-muted-foreground text-xs">
          <FileText className="h-3 w-3 shrink-0" />
          <span className="line-clamp-1">{val || '-'}</span>
        </div>
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
            onClick={() => window.location.href = routes.edit.replace(':id', id.toString())}
          >
            <Edit2 className="h-4 w-4" />
          </Button>
          <form
            action={routes.destroy.replace(':id', id.toString())}
            method="POST"
            onSubmit={(e) => {
              if (!confirm('Apakah Anda yakin ingin menghapus vendor ini?')) {
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
      data={vendors}
      columns={columns}
      searchPlaceholder="Cari nama vendor atau kontak..."
    />
  );
};
