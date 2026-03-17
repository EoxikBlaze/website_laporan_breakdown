import React, { useState, useEffect } from "react";
import { Sidebar, SidebarBody, SidebarLink } from "@/components/ui/sidebar";
import { 
  LayoutDashboard, 
  Truck, 
  Building2, 
  Wrench, 
  ChevronDown,
  ListOrdered, 
  LogOut,
  Users,
  PlusCircle,
} from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";
import { cn } from "@/lib/utils";

interface AppSidebarProps {
  user: {
    name: string;
    email: string;
    role: string;
  };
  routes: {
    dashboard: string;
    units: string;
    vendors: string;
    breakdownCreate: string;
    breakdownIndex: string;
    users: string;
    logout: string;
  };
  csrfToken: string;
  canAdmin: boolean;
  currentRoute: string;
}

export const AppSidebar = ({ user, routes, csrfToken, canAdmin, currentRoute }: AppSidebarProps) => {
  const [open, setOpen] = useState(false);
  const [breakdownOpen, setBreakdownOpen] = useState(
    currentRoute.includes('breakdown') || currentRoute.includes('dashboard')
  );

  const isActiveBreakdown = currentRoute.includes('breakdown') || currentRoute.includes('dashboard');
  
  const [isMobile, setIsMobile] = useState(false);

  useEffect(() => {
    const checkMobile = () => setIsMobile(window.innerWidth < 768);
    checkMobile();
    window.addEventListener('resize', checkMobile);
    return () => window.removeEventListener('resize', checkMobile);
  }, []);

  return (
    <Sidebar open={open} setOpen={setOpen}>
      <SidebarBody className="justify-between gap-10 border-r border-neutral-200">
        <div className={cn("flex flex-col flex-1 overflow-y-auto overflow-x-hidden", !open && "items-center")}>
          {open ? <Logo /> : <LogoIcon />}
          
          <div className="mt-8 flex flex-col gap-1">
            
            {/* ── Breakdown Dropdown ── */}
            <div>
              <button
                onClick={() => setBreakdownOpen(!breakdownOpen)}
                className={cn(
                  "flex items-center gap-2 py-3 md:py-2 w-full rounded-lg transition-colors hover:bg-neutral-100",
                  open ? "justify-start px-3" : "justify-center px-0",
                  isActiveBreakdown && "bg-primary/10"
                )}
              >
                <Wrench className={cn("h-5 w-5 flex-shrink-0 transition-colors", isActiveBreakdown ? "text-primary" : "text-neutral-700")} />
                {open && (
                  <motion.span
                    animate={{ opacity: 1 }}
                    initial={{ opacity: 0 }}
                    className="text-neutral-700 text-sm flex-1 text-left"
                  >
                    Breakdown
                  </motion.span>
                )}
                {open && (
                  <motion.div animate={{ rotate: breakdownOpen ? 180 : 0 }} transition={{ duration: 0.2 }}>
                    <ChevronDown className="h-4 w-4 text-neutral-400" />
                  </motion.div>
                )}
              </button>

              <AnimatePresence initial={false}>
                {(breakdownOpen && open) && (
                  <motion.div
                    initial={{ height: 0, opacity: 0 }}
                    animate={{ height: "auto", opacity: 1 }}
                    exit={{ height: 0, opacity: 0 }}
                    transition={{ duration: 0.2 }}
                    className="overflow-hidden pl-4 mt-1 flex flex-col gap-1"
                  >
                    <a href={routes.dashboard}
                      className={cn(
                        "flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors hover:bg-neutral-100",
                        currentRoute.includes('dashboard') ? "bg-primary/10 text-primary font-medium" : "text-neutral-600"
                      )}>
                      <LayoutDashboard className="h-4 w-4 flex-shrink-0" />
                      Dashboard
                    </a>
                    <a href={routes.breakdownCreate}
                      className={cn(
                        "flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors hover:bg-neutral-100",
                        currentRoute.includes('breakdown') && currentRoute.includes('create') ? "bg-primary/10 text-primary font-medium" : "text-neutral-600"
                      )}>
                      <PlusCircle className="h-4 w-4 flex-shrink-0" />
                      Input Laporan
                    </a>
                    <a href={routes.breakdownIndex}
                      className={cn(
                        "flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors hover:bg-neutral-100",
                        currentRoute.includes('breakdown_logs') && !currentRoute.includes('create') && !currentRoute.includes('edit') ? "bg-primary/10 text-primary font-medium" : "text-neutral-600"
                      )}>
                      <ListOrdered className="h-4 w-4 flex-shrink-0" />
                      Daftar Laporan
                    </a>
                  </motion.div>
                )}
              </AnimatePresence>
            </div>

            {/* ── Admin-only links ── */}
            {canAdmin && (
              <>
                <SidebarLink 
                  link={{
                    label: "Data Unit",
                    href: routes.units,
                    icon: <Truck className={cn("h-5 w-5 flex-shrink-0 transition-colors", currentRoute.includes('master_units') ? "text-primary" : "text-neutral-700")} />,
                  }} 
                  className={cn(
                    "py-3 md:py-2 rounded-lg transition-colors hover:bg-neutral-100",
                    currentRoute.includes('master_units') && "bg-primary/10"
                  )}
                />
                <SidebarLink 
                  link={{
                    label: "Data Vendor",
                    href: routes.vendors,
                    icon: <Building2 className={cn("h-5 w-5 flex-shrink-0 transition-colors", currentRoute.includes('vendors') ? "text-primary" : "text-neutral-700")} />,
                  }} 
                  className={cn(
                    "py-3 md:py-2 rounded-lg transition-colors hover:bg-neutral-100",
                    currentRoute.includes('vendors') && "bg-primary/10"
                  )}
                />
                <SidebarLink 
                  link={{
                    label: "Manajemen User",
                    href: routes.users,
                    icon: <Users className={cn("h-5 w-5 flex-shrink-0 transition-colors", currentRoute.includes('users') ? "text-primary" : "text-neutral-700")} />,
                  }} 
                  className={cn(
                    "py-3 md:py-2 rounded-lg transition-colors hover:bg-neutral-100",
                    currentRoute.includes('/users') && "bg-primary/10"
                  )}
                />
              </>
            )}
          </div>
        </div>

        <div className="flex flex-col gap-4">
          <div className="pt-4 border-t border-neutral-200">
            <SidebarLink
              link={{
                label: user.name,
                href: "#",
                icon: (
                  <div className="h-7 w-7 flex-shrink-0 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                     {user.name.charAt(0).toUpperCase()}
                  </div>
                ),
              }}
            />
            {open && (
               <div className="px-9 -mt-2 mb-2">
                 <p className="text-[10px] text-muted-foreground truncate">{user.email}</p>
                 <p>
                   {user.role === 'super_admin' ? 'Super Admin' : (user.role === 'vendor_admin' ? 'Admin Vendor' : 'Operator')}
                 </p>
               </div>
            )}
          </div>
          
          <form action={routes.logout} method="POST" className="w-full">
            <input type="hidden" name="_token" value={csrfToken} />
            <button 
              type="submit"
              className={cn(
                "flex items-center gap-2 group/sidebar py-2 w-full rounded-lg hover:bg-rose-50 text-rose-600 transition-colors",
                open ? "justify-start px-2" : "justify-center px-0"
              )}
            >
              <LogOut className="h-5 w-5 flex-shrink-0" />
              {open && (
                <motion.span
                  initial={{ opacity: 0 }}
                  animate={{ opacity: 1 }}
                  className="text-sm font-medium"
                >
                  Logout
                </motion.span>
              )}
            </button>
          </form>
        </div>
      </SidebarBody>
    </Sidebar>
  );
};

export const Logo = () => {
  return (
    <div className="font-normal flex space-x-2 items-center text-sm text-black py-1 relative z-20">
      <div className="h-6 w-7 bg-primary rounded-br-lg rounded-tr-sm rounded-tl-lg rounded-bl-sm flex-shrink-0 flex items-center justify-center text-white">
        <Building2 className="h-4 w-4" />
      </div>
      <motion.span
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        className="font-bold text-sm text-black leading-tight"
      >
        General Service <span className="text-primary text-[10px] block font-medium tracking-tight uppercase">PT. PAMA SITE . ARIA</span>
      </motion.span>
    </div>
  );
};

export const LogoIcon = () => {
  return (
    <div className="font-normal flex space-x-2 items-center text-sm text-black py-1 relative z-20">
      <div className="h-6 w-7 bg-blue-600 rounded-br-lg rounded-tr-sm rounded-tl-lg rounded-bl-sm flex-shrink-0 flex items-center justify-center text-white">
        <Building2 className="h-4 w-4" />
      </div>
    </div>
  );
};
