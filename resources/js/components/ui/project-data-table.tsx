"use client";

import * as React from "react";
import { motion } from "framer-motion";
import { cn } from "@/lib/utils";
import { cva, type VariantProps } from "class-variance-authority";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Badge } from "@/components/ui/badge";
import { ExternalLink } from "lucide-react";

// --- TYPE DEFINITIONS ---
export interface Contributor {
  src: string;
  alt: string;
  fallback: string;
}

export type StatusVariant = "active" | "inProgress" | "onHold" | "success" | "error" | "warning" | "default";

export interface ProjectProject {
  id: string;
  name: string;
  repository: string;
  team: string;
  tech: string;
  createdAt: string;
  contributors: Contributor[];
  status: {
    text: string;
    variant: StatusVariant;
  };
}

// --- PROPS INTERFACE ---
interface ProjectDataTableProps {
  projects: ProjectProject[];
  visibleColumns: Set<string>;
}

// --- STATUS BADGE VARIANTS ---
const badgeVariants = cva("capitalize text-white px-2 py-0.5 rounded-full text-[10px]", {
  variants: {
    variant: {
      active: "bg-green-500 hover:bg-green-600",
      inProgress: "bg-yellow-500 hover:bg-yellow-600",
      onHold: "bg-red-500 hover:bg-red-600",
      success: "bg-emerald-500 hover:bg-emerald-600",
      error: "bg-rose-500 hover:bg-rose-600",
      warning: "bg-amber-500 hover:bg-amber-600",
      default: "bg-slate-500 hover:bg-slate-600",
    },
  },
  defaultVariants: {
    variant: "active",
  },
});

// --- MAIN COMPONENT ---
export const ProjectDataTable = ({ projects, visibleColumns }: ProjectDataTableProps) => {
  // Animation variants for table rows
  const rowVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: (i: number) => ({
      opacity: 1,
      y: 0,
      transition: {
        delay: i * 0.05,
        duration: 0.3,
        ease: "easeInOut" as const,
      },
    }),
  };
  
  const tableHeaders: { key: string; label: string }[] = [
    { key: "name", label: "Project" },
    { key: "repository", label: "Repository" },
    { key: "team", label: "Team" },
    { key: "tech", label: "Tech" },
    { key: "createdAt", label: "Created At" },
    { key: "contributors", label: "Contributors" },
    { key: "status", label: "Status" },
  ];

  return (
    <div className="rounded-xl border bg-card text-card-foreground shadow-sm overflow-hidden border-border/50">
      <div className="relative w-full overflow-auto">
        <Table>
          <TableHeader className="bg-muted/50">
            <TableRow>
              {tableHeaders
                .filter((header) => visibleColumns.has(header.key))
                .map((header) => (
                  <TableHead key={header.key} className="py-4 font-semibold text-foreground/80">{header.label}</TableHead>
                ))}
            </TableRow>
          </TableHeader>
          <TableBody>
            {projects.length > 0 ? (
              projects.map((project, index) => (
                <motion.tr
                  key={project.id}
                  custom={index}
                  initial="hidden"
                  animate="visible"
                  variants={rowVariants}
                  className="border-b transition-colors hover:bg-muted/30 data-[state=selected]:bg-muted"
                >
                  {visibleColumns.has("name") && <TableCell className="font-medium py-4">{project.name}</TableCell>}
                  
                  {visibleColumns.has("repository") && (
                    <TableCell className="py-4">
                      <a
                        href={project.repository}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="flex items-center gap-2 text-muted-foreground transition-colors hover:text-primary"
                      >
                        <span className="truncate max-w-xs">{project.repository.replace('https://', '')}</span>
                        <ExternalLink className="h-3.5 w-3.5 flex-shrink-0" />
                      </a>
                    </TableCell>
                  )}
                  
                  {visibleColumns.has("team") && <TableCell className="py-4">{project.team}</TableCell>}
                  {visibleColumns.has("tech") && <TableCell className="py-4 font-mono text-xs">{project.tech}</TableCell>}
                  {visibleColumns.has("createdAt") && <TableCell className="py-4 text-muted-foreground">{project.createdAt}</TableCell>}
                  
                  {visibleColumns.has("contributors") && (
                    <TableCell className="py-4">
                      <div className="flex -space-x-2">
                        {project.contributors.map((contributor, idx) => (
                          <Avatar key={idx} className="h-7 w-7 border-2 border-background shadow-sm hover:z-10 transition-all hover:scale-110">
                            <AvatarImage src={contributor.src} alt={contributor.alt} />
                            <AvatarFallback>{contributor.fallback}</AvatarFallback>
                          </Avatar>
                        ))}
                      </div>
                    </TableCell>
                  )}

                  {visibleColumns.has("status") && (
                    <TableCell className="py-4">
                      <Badge className={cn(badgeVariants({ variant: project.status.variant as any }), "border-0 shadow-sm font-medium")}>
                        {project.status.text}
                      </Badge>
                    </TableCell>
                  )}
                </motion.tr>
              ))
            ) : (
              <TableRow>
                <TableCell colSpan={visibleColumns.size} className="h-32 text-center text-muted-foreground">
                  No results found.
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
      </div>
    </div>
  );
};
