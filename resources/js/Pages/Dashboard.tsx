"use client";
import React, { useState } from "react";
import { Sidebar, SidebarBody, SidebarLink } from "@/Components/ui/sidebar";
import { LayoutDashboard, UserCog, Settings, LogOut, Truck, Wrench, Building2 } from "lucide-react";
import { Link, Head } from "@inertiajs/react";
import { motion } from "framer-motion";
import { cn } from "@/lib/utils";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function Dashboard({ auth, stats }: { auth: any, stats: any }) {
  const links = [
    {
      label: "Dashboard",
      href: route('dashboard'),
      icon: (
        <LayoutDashboard className="text-neutral-700 dark:text-neutral-200 h-5 w-5 flex-shrink-0" />
      ),
    },
    {
      label: "Unit Rusak",
      href: route('breakdown_logs.index'),
      icon: (
        <Wrench className="text-neutral-700 dark:text-neutral-200 h-5 w-5 flex-shrink-0" />
      ),
    },
    {
      label: "Master Unit",
      href: route('master_units.index'),
      icon: (
        <Truck className="text-neutral-700 dark:text-neutral-200 h-5 w-5 flex-shrink-0" />
      ),
    },
    {
      label: "Data Vendor",
      href: route('vendors.index'),
      icon: (
        <Building2 className="text-neutral-700 dark:text-neutral-200 h-5 w-5 flex-shrink-0" />
      ),
    },
    {
      label: "Logout",
      href: route('logout'),
      icon: (
        <LogOut className="text-neutral-700 dark:text-neutral-200 h-5 w-5 flex-shrink-0" />
      ),
    },
  ];

  const [open, setOpen] = useState(false);

  return (
    <div className="flex flex-col md:flex-row bg-gray-100 dark:bg-neutral-800 w-full flex-1 max-w-full mx-auto border border-neutral-200 dark:border-neutral-700 overflow-hidden h-screen">
      <Head title="Dashboard" />
      <Sidebar open={open} setOpen={setOpen}>
        <SidebarBody className="justify-between gap-10">
          <div className="flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            {open ? <Logo /> : <LogoIcon />}
            <div className="mt-8 flex flex-col gap-2">
              {links.map((link, idx) => (
                <SidebarLink key={idx} link={link} />
              ))}
            </div>
          </div>
          <div>
            <SidebarLink
              link={{
                label: auth.user.name,
                href: route('profile.edit'),
                icon: (
                  <div className="h-7 w-7 flex-shrink-0 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold">
                    {auth.user.name.substring(0, 2).toUpperCase()}
                  </div>
                ),
              }}
            />
          </div>
        </SidebarBody>
      </Sidebar>
      <MainContent stats={stats} />
    </div>
  );
}

// ... (Logo and LogoIcon remain the same)

export const Logo = () => {
  return (
    <Link
      href="/"
      className="font-normal flex space-x-2 items-center text-sm text-black py-1 relative z-20"
    >
      <div className="h-5 w-6 bg-black dark:bg-white rounded-br-lg rounded-tr-sm rounded-tl-lg rounded-bl-sm flex-shrink-0" />
      <motion.span
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        className="font-medium text-black dark:text-white whitespace-pre"
      >
        PAMA Breakdown
      </motion.span>
    </Link>
  );
};

export const LogoIcon = () => {
  return (
    <Link
      href="/"
      className="font-normal flex space-x-2 items-center text-sm text-black py-1 relative z-20"
    >
      <div className="h-5 w-6 bg-black dark:bg-white rounded-br-lg rounded-tr-sm rounded-tl-lg rounded-bl-sm flex-shrink-0" />
    </Link>
  );
};

const MainContent = ({ stats }: { stats: any }) => {
  return (
    <div className="flex flex-1 overflow-y-auto">
      <div className="p-4 md:p-10 rounded-tl-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 flex flex-col gap-6 flex-1 w-full h-full min-h-screen">
        <div>
          <h1 className="text-2xl font-bold text-neutral-800 dark:text-neutral-100">PAMA Breakdown Monitoring</h1>
          <p className="text-neutral-500 dark:text-neutral-400">Selamat datang kembali! Berikut adalah ringkasan unit saat ini.</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <StatCard 
            title="Total Unit" 
            value={stats.total_units} 
            icon={<Truck className="text-blue-600" />} 
            color="bg-blue-50 border-blue-100" 
          />
          <StatCard 
            title="Breakdown Aktif" 
            value={stats.active_breakdowns} 
            icon={<Wrench className="text-red-600" />} 
            color="bg-red-50 border-red-100" 
            textColor="text-red-700"
          />
          <StatCard 
            title="Total Vendor" 
            value={stats.total_vendors} 
            icon={<Building2 className="text-green-600" />} 
            color="bg-green-50 border-green-100" 
          />
          <StatCard 
            title="Total Laporan" 
            value={stats.total_reports} 
            icon={<LayoutDashboard className="text-purple-600" />} 
            color="bg-purple-50 border-purple-100" 
          />
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 flex-1">
            <div className="lg:col-span-2 rounded-xl bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 p-6 shadow-sm">
                <h2 className="text-lg font-bold mb-4">Tren Breakdown</h2>
                <div className="h-64 bg-gray-200 dark:bg-neutral-700 rounded-lg animate-pulse flex items-center justify-center text-gray-500">
                    Sistem Grafis Sedang Disiapkan...
                </div>
            </div>
            <div className="rounded-xl bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 p-6 shadow-sm">
                <h2 className="text-lg font-bold mb-4">Aktivitas Terkini</h2>
                <ul className="space-y-4">
                    {[1, 2, 3, 4].map((i) => (
                        <li key={i} className="flex gap-3 items-center p-2 rounded-lg hover:bg-white dark:hover:bg-neutral-700 transition-colors">
                            <div className="h-10 w-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600">
                                <Wrench size={18} />
                            </div>
                            <div>
                                <p className="text-sm font-semibold">Update Unit Breakdown</p>
                                <p className="text-xs text-gray-500 italic">Beberapa saat yang lalu</p>
                            </div>
                        </li>
                    ))}
                </ul>
            </div>
        </div>
      </div>
    </div>
  );
};

const StatCard = ({ title, value, icon, color, textColor = "text-neutral-800" }: any) => (
  <div className={`p-6 rounded-xl border ${color} flex items-center gap-4 shadow-sm`}>
    <div className="p-3 bg-white dark:bg-neutral-800 rounded-lg shadow-inner">
      {icon}
    </div>
    <div>
      <p className="text-sm text-neutral-500 font-medium">{title}</p>
      <p className={`text-2xl font-bold ${textColor}`}>{value}</p>
    </div>
  </div>
);
