import * as React from "react";
import { PremiumDataTable, Column } from "@/components/ui/premium-data-table";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { 
  Search, 
  Eye, 
  Edit2, 
  Trash2, 
  Clock, 
  Calendar, 
  CheckCircle2, 
  AlertCircle, 
  Truck,
  Building2,
  User,
  History
} from "lucide-react";
import { format } from "date-fns";
import { cn } from "@/lib/utils";

interface BreakdownLog {
  id: number;
  unit: { nomor_lambung: string };
  keterangan: string;
  spare_unit?: { nomor_lambung: string };
  vendor?: { nama_vendor: string };
  reporter?: { name: string };
  waktu_awal_bd: string;
  waktu_akhir_bd: string | null;
  loss_time: string | null;
  loss_time_percentage: string | null;
  status: string;
}

interface BreakdownLogTableProps {
  logs: any[];
  canAdmin: boolean;
  routes: {
    show: string;
    edit: string;
    destroy: string;
  };
  csrfToken: string;
}

export const BreakdownLogTable = ({ logs, canAdmin, routes, csrfToken }: BreakdownLogTableProps) => {
  const columns: Column[] = [
    {
      key: "unit",
      label: "Unit Rusak",
      render: (val) => (
        <div className="flex items-center gap-2">
          <Truck className="h-4 w-4 text-primary/70" />
          <span className="font-bold underline decoration-primary/30 underline-offset-4">{val.nomor_lambung}</span>
        </div>
      )
    },
    {
      key: "keterangan",
      label: "Keterangan",
      render: (val) => <span className="text-muted-foreground italic text-xs line-clamp-1">{val || '-'}</span>
    },
    {
      key: "spare_unit",
      label: "Unit Pengganti",
      render: (val) => (
        val ? (
          <Badge variant="outline" className="border-primary/20 bg-primary/5 text-primary">
            {val.nomor_lambung}
          </Badge>
        ) : (
          <span className="text-[10px] text-muted-foreground">-</span>
        )
      )
    },
    {
      key: "vendor",
      label: "Vendor",
      render: (val) => (
        val ? (
          <div className="flex items-center gap-1.5 text-info font-medium text-xs">
            <Building2 className="h-3 w-3" />
            {val.nama_vendor}
          </div>
        ) : (
          <span className="text-[10px] text-muted-foreground uppercase tracking-tight">Internal</span>
        )
      )
    },
  ];

  if (canAdmin) {
    columns.push({
      key: "reporter",
      label: "Pelapor",
      render: (val) => (
        <div className="flex items-center gap-1 text-[11px] text-muted-foreground">
          <User className="h-3 w-3" />
          {val?.name || 'System'}
        </div>
      )
    });
  }

  columns.push(
    {
      key: "waktu_awal_bd",
      label: "Waktu Mulai",
      render: (val) => (
        <div className="flex flex-col text-[10px]">
          <span className="font-medium text-foreground">{format(new Date(val), 'dd/MM/yyyy')}</span>
          <span className="text-muted-foreground">{format(new Date(val), 'HH:mm')}</span>
        </div>
      )
    },
    {
      key: "waktu_akhir_bd",
      label: "Waktu Selesai",
      render: (val) => (
        val ? (
          <div className="flex flex-col text-[10px]">
            <span className="font-medium text-foreground">{format(new Date(val), 'dd/MM/yyyy')}</span>
            <span className="text-muted-foreground">{format(new Date(val), 'HH:mm')}</span>
          </div>
        ) : (
          <Badge variant="warning" className="text-[9px] px-1.5 py-0">Berlangsung</Badge>
        )
      )
    },
    {
      key: "loss_time",
      label: "Loss Time",
      render: (val, row) => (
        <div className="flex flex-col">
          <span className={cn("font-mono text-sm", val ? "text-rose-600 font-bold" : "text-muted-foreground")}>
            {val || "-"}
          </span>
          {row.loss_time_percentage && (
            <span className="text-[10px] text-info font-medium">{row.loss_time_percentage}</span>
          )}
        </div>
      )
    },
    {
      key: "status",
      label: "Status",
      render: (val) => (
        val === 'Open' ? (
          <Badge className="bg-rose-500 hover:bg-rose-600 text-white border-0 shadow-sm flex items-center gap-1 w-fit">
            <AlertCircle className="h-3 w-3" />
            Open
          </Badge>
        ) : (
          <Badge className="bg-emerald-500 hover:bg-emerald-600 text-white border-0 shadow-sm flex items-center gap-1 w-fit">
            <CheckCircle2 className="h-3 w-3" />
            Closed
          </Badge>
        )
      )
    },
    {
      key: "id",
      label: "Aksi",
      render: (id) => (
        <div className="flex items-center gap-1">
          <Button
            variant="ghost"
            size="icon"
            className="h-7 w-7 text-primary hover:bg-primary/10"
            onClick={() => window.location.href = routes.show.replace(':id', id.toString())}
          >
            <Eye className="h-3.5 w-3.5" />
          </Button>
          <Button
            variant="ghost"
            size="icon"
            className="h-7 w-7 text-info hover:bg-info/10"
            onClick={() => window.location.href = routes.edit.replace(':id', id.toString())}
          >
            <Edit2 className="h-3.5 w-3.5" />
          </Button>
          <form
            action={routes.destroy.replace(':id', id.toString())}
            method="POST"
            onSubmit={(e) => {
              if (!confirm('Hapus laporan ini?')) {
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
              className="h-7 w-7 text-destructive hover:bg-destructive/10"
            >
              <Trash2 className="h-3.5 w-3.5" />
            </Button>
          </form>
        </div>
      )
    }
  );

  return (
    <PremiumDataTable
      data={logs}
      columns={columns}
      searchPlaceholder="Cari unit, keterangan, atau vendor..."
    />
  );
};
