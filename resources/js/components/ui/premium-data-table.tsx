"use client";

import * as React from "react";
import { motion, AnimatePresence } from "framer-motion";
import { cn } from "@/lib/utils";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Input } from "@/components/ui/input";
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Button } from "@/components/ui/button";
import { ListFilter, Columns, Search, MoreHorizontal } from "lucide-react";

export interface Column {
  key: string;
  label: string;
  render?: (value: any, row: any) => React.ReactNode;
}

interface PremiumDataTableProps {
  data: any[];
  columns: Column[];
  searchPlaceholder?: string;
  filterableKey?: string;
}

export const PremiumDataTable = ({ 
  data, 
  columns, 
  searchPlaceholder = "Search...", 
  filterableKey 
}: PremiumDataTableProps) => {
  const [searchTerm, setSearchTerm] = React.useState("");
  const [visibleColumns, setVisibleColumns] = React.useState<Set<string>>(
    new Set(columns.map(c => c.key))
  );

  const filteredData = React.useMemo(() => {
    return data.filter((item) => {
      const searchStr = JSON.stringify(item).toLowerCase();
      return searchStr.includes(searchTerm.toLowerCase());
    });
  }, [data, searchTerm]);

  const toggleColumn = (key: string) => {
    setVisibleColumns((prev) => {
      const newSet = new Set(prev);
      if (newSet.has(key)) {
        if (newSet.size > 1) newSet.delete(key);
      } else {
        newSet.add(key);
      }
      return newSet;
    });
  };

  const containerVariants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        staggerChildren: 0.05
      }
    }
  };

  const rowVariants = {
    hidden: { opacity: 0, y: 10 },
    visible: { opacity: 1, y: 0 }
  };

  return (
    <div className="space-y-4">
      <div className="flex flex-col gap-4 sm:flex-row sm:items-center justify-between">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder={searchPlaceholder}
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="pl-9 bg-background/50 backdrop-blur-sm border-border/50 focus-visible:ring-primary/20"
          />
        </div>
        
        <div className="flex items-center gap-2">
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="outline" size="sm" className="h-9 gap-2 border-border/50 bg-background/50 backdrop-blur-sm">
                <Columns className="h-4 w-4" />
                <span>Kolom</span>
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-48">
              <DropdownMenuLabel>Tampilkan Kolom</DropdownMenuLabel>
              <DropdownMenuSeparator />
              {columns.map((column) => (
                <DropdownMenuCheckboxItem
                  key={column.key}
                  checked={visibleColumns.has(column.key)}
                  onCheckedChange={() => toggleColumn(column.key)}
                >
                  {column.label}
                </DropdownMenuCheckboxItem>
              ))}
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </div>

      <div className="rounded-xl border bg-card/30 backdrop-blur-sm text-card-foreground shadow-xl border-border/40 overflow-hidden">
        <div className="relative w-full overflow-auto">
          <Table>
            <TableHeader className="bg-blue-50/50 sticky top-0 z-10 backdrop-blur-md border-b-2 border-primary/10">
              <TableRow className="hover:bg-transparent border-border/40">
                {columns
                  .filter((col) => visibleColumns.has(col.key))
                  .map((col) => (
                    <TableHead key={col.key} className="h-12 px-4 py-3 font-bold text-primary uppercase text-[10px] tracking-wider">
                      {col.label}
                    </TableHead>
                  ))}
              </TableRow>
            </TableHeader>
            <TableBody>
              <AnimatePresence mode="popLayout">
                {filteredData.length > 0 ? (
                  filteredData.map((row, index) => (
                    <motion.tr
                      key={row.id || index}
                      initial="hidden"
                      animate="visible"
                      variants={rowVariants}
                      layout
                      className="border-b border-border/30 transition-colors hover:bg-muted/20 data-[state=selected]:bg-muted group"
                    >
                      {columns
                        .filter((col) => visibleColumns.has(col.key))
                        .map((col) => (
                          <TableCell key={col.key} className="px-4 py-4 truncate max-w-[250px]">
                            {col.render ? col.render(row[col.key], row) : row[col.key]}
                          </TableCell>
                        ))}
                    </motion.tr>
                  ))
                ) : (
                  <TableRow className="hover:bg-transparent border-0">
                    <TableCell colSpan={visibleColumns.size} className="h-32 text-center text-muted-foreground italic">
                      Data tidak ditemukan.
                    </TableCell>
                  </TableRow>
                )}
              </AnimatePresence>
            </TableBody>
          </Table>
        </div>
      </div>
      <div className="text-[11px] text-muted-foreground px-1">
        Menampilkan {filteredData.length} dari {data.length} total data
      </div>
    </div>
  );
};
