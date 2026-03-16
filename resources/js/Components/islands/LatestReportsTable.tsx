import * as React from "react";
import { PremiumDataTable, Column } from "@/Components/ui/premium-data-table";
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { Eye, Truck, AlertCircle, CheckCircle2 } from "lucide-react";

interface LatestReport {
  id: number;
  unit?: { nomor_lambung: string };
  jenis_breakdown: string;
  vendor?: { nama_vendor: string };
  status: string;
}

interface LatestReportsTableProps {
  reports: LatestReport[];
  showRoute: string;
}

export const LatestReportsTable = ({ reports, showRoute }: LatestReportsTableProps) => {
  const columns: Column[] = [
    {
      key: "unit",
      label: "No. Unit",
      render: (val) => (
        <div className="flex items-center gap-2">
          <Truck className="h-3.5 w-3.5 text-primary/70" />
          <span className="font-bold text-xs">{val?.nomor_lambung || '-'}</span>
        </div>
      )
    },
    {
      key: "jenis_breakdown",
      label: "Jenis Breakdown",
      render: (val) => <span className="text-[11px] font-medium">{val}</span>
    },
    {
      key: "vendor",
      label: "Vendor",
      render: (val) => (
        <span className="text-[10px] text-muted-foreground">
          {val?.nama_vendor || 'Internal'}
        </span>
      )
    },
    {
      key: "status",
      label: "Status",
      render: (val) => (
        val === 'Open' ? (
          <Badge className="bg-rose-500 hover:bg-rose-600 text-white border-0 shadow-sm text-[9px] px-2 py-0">
            Open
          </Badge>
        ) : (
          <Badge className="bg-emerald-500 hover:bg-emerald-600 text-white border-0 shadow-sm text-[9px] px-2 py-0">
            Closed
          </Badge>
        )
      )
    },
    {
      key: "id",
      label: "Aksi",
      render: (id) => (
        <Button
          variant="outline"
          size="sm"
          className="h-7 px-3 text-[10px] rounded-full gap-1 items-center border-primary/30 text-primary hover:bg-primary/5"
          onClick={() => window.location.href = showRoute.replace(':id', id.toString())}
        >
          <Eye className="h-3 w-3" />
          Detail
        </Button>
      )
    }
  ];

  return (
    <PremiumDataTable
      data={reports}
      columns={columns}
      searchPlaceholder="Cari laporan..."
    />
  );
};
